<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\HttpFoundation\File;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;

/**
 * @author Bernhard Schussek <bernhard.schussek@symfony-project.com>
 */
class TemporaryStorage
{
    private $directory;
    private $secret;

    public function __construct($secret, $directory = null)
    {
        if (empty($directory)) {
            $directory = sys_get_temp_dir();
        }

        $this->directory = realpath($directory);
        $this->secret = $secret;
    }

    protected function generateHashInfo($token)
    {
        return $this->secret.$token;
    }

    protected function generateHash($token)
    {
        return md5($this->generateHashInfo($token));
    }

    public function getTempDir($token)
    {
        if (!is_string($token)) {
            throw new UnexpectedTypeException($token, 'string');
        }

        $hash = $this->generateHash($token);

        return $this->directory.DIRECTORY_SEPARATOR.substr($hash, 0, 2).DIRECTORY_SEPARATOR.substr($hash, 2);
    }
}
