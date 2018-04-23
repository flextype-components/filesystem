<?php

/**
 * @package Flextype Components
 *
 * @author Sergey Romanenko <awilum@yandex.ru>
 * @link http://components.flextype.org
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Flextype\Component\Filesystem;

class Filesystem
{
    /**
     * Mime type list
     *
     * @var array
     */
    public static $mime_types = [
        'aac'        => 'audio/aac',
        'atom'       => 'application/atom+xml',
        'avi'        => 'video/avi',
        'bmp'        => 'image/x-ms-bmp',
        'c'          => 'text/x-c',
        'class'      => 'application/octet-stream',
        'css'        => 'text/css',
        'csv'        => 'text/csv',
        'deb'        => 'application/x-deb',
        'dll'        => 'application/x-msdownload',
        'dmg'        => 'application/x-apple-diskimage',
        'doc'        => 'application/msword',
        'docx'       => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'exe'        => 'application/octet-stream',
        'flv'        => 'video/x-flv',
        'gif'        => 'image/gif',
        'gz'         => 'application/x-gzip',
        'h'          => 'text/x-c',
        'htm'        => 'text/html',
        'html'       => 'text/html',
        'ini'        => 'text/plain',
        'jar'        => 'application/java-archive',
        'java'       => 'text/x-java',
        'jpeg'       => 'image/jpeg',
        'jpg'        => 'image/jpeg',
        'js'         => 'text/javascript',
        'json'       => 'application/json',
        'mid'        => 'audio/midi',
        'midi'       => 'audio/midi',
        'mka'        => 'audio/x-matroska',
        'mkv'        => 'video/x-matroska',
        'mp3'        => 'audio/mpeg',
        'mp4'        => 'application/mp4',
        'mpeg'       => 'video/mpeg',
        'mpg'        => 'video/mpeg',
        'odt'        => 'application/vnd.oasis.opendocument.text',
        'ogg'        => 'audio/ogg',
        'pdf'        => 'application/pdf',
        'php'        => 'text/x-php',
        'png'        => 'image/png',
        'psd'        => 'image/vnd.adobe.photoshop',
        'py'         => 'application/x-python',
        'ra'         => 'audio/vnd.rn-realaudio',
        'ram'        => 'audio/vnd.rn-realaudio',
        'rar'        => 'application/x-rar-compressed',
        'rss'        => 'application/rss+xml',
        'safariextz' => 'application/x-safari-extension',
        'sh'         => 'text/x-shellscript',
        'shtml'      => 'text/html',
        'swf'        => 'application/x-shockwave-flash',
        'tar'        => 'application/x-tar',
        'tif'        => 'image/tiff',
        'tiff'       => 'image/tiff',
        'torrent'    => 'application/x-bittorrent',
        'txt'        => 'text/plain',
        'wav'        => 'audio/wav',
        'webp'       => 'image/webp',
        'wma'        => 'audio/x-ms-wma',
        'xls'        => 'application/vnd.ms-excel',
        'xml'        => 'text/xml',
        'zip'        => 'application/zip',
    ];

    /**
     * Returns true if the File exists.
     *
     * if (Filesystem::fileExists('filename.txt')) {
     *     // Do something...
     * }
     *
     * @param  string  $filename The file name
     * @return bool
     */
    public static function fileExists(string $filename) : bool
    {
        return (file_exists($filename) && is_file($filename));
    }

    /**
     * Delete file
     *
     * Filesystem::deleteFile('filename.txt');
     *
     * @param  mixed $filename The file name or array of files
     * @return bool
     */
    public static function deleteFile($filename) : bool
    {
        // Is array
        if (is_array($filename)) {

            // Delete each file in $filename array
            foreach ($filename as $file) {
                @unlink((string) $file);
            }
        } else {
            // Is string
            return @unlink((string) $filename);
        }
    }

    /**
     * Rename file
     *
     * Filesystem::renameFile('filename1.txt', 'filename2.txt');
     *
     * @param  string  $from Original file location
     * @param  string  $to   Desitination location of the file
     * @return bool
     */
    public static function renameFile(string $from, string $to) : bool
    {

        // If file exists $to than rename it
        if (! Filesystem::fileExists($to)) {
            return rename($from, $to);
        }

        // Else return false
        return false;
    }

    /**
     * Copy file
     *
     * Filesystem::copyFile('folder1/filename.txt', 'folder2/filename.txt');
     *
     * @param  string  $from Original file location
     * @param  string  $to   Desitination location of the file
     * @return bool
     */
    public static function copy(string $from, string $to) : bool
    {
        // If file !exists $from and exists $to then return false
        if (! Filesystem::fileExists($from) || Filesystem::fileExists($to)) {
            return false;
        }

        // Else copy file
        return copy($from, $to);
    }

    /**
     * Get the File extension.
     *
     * echo Filesystem::fileExt('filename.txt');
     *
     * @param  string $filename The file name
     * @return string
     */
    public static function fileExt(string $filename) : string
    {
        // Return file extension
        return substr(strrchr($filename, '.'), 1);
    }

    /**
     * Get the File name
     *
     * echo Filesystem::filename('filename.txt');
     *
     * @param  string $filename The file name
     * @return string
     */
    public static function filename(string $filename) : string
    {
        // Return filename
        return basename($filename, '.'.Filesystem::fileExt($filename));
    }

    /**
     * Get list of files in directory recursive
     *
     * $files = Filesystem::getFilesList('folder');
     * $files = Filesystem::getFilesList('folder', 'txt');
     * $files = Filesystem::getFilesList('folder', array('txt', 'log'));
     *
     * @param  string $folder      Folder
     * @param  mixed  $type        Files types
     * @param  mixed  $file_path   Files path
     * @return mixed
     */
    public static function getFilesList(string $folder, $type = null, $file_path = true)
    {
        $data = array();
        if (is_dir($folder)) {
            $iterator = new \RecursiveDirectoryIterator($folder);
            foreach (new \RecursiveIteratorIterator($iterator) as $file) {
                if ($type !== null) {
                    if (is_array($type)) {
                        $file_ext = substr(strrchr($file->getFilename(), '.'), 1);
                        if (in_array($file_ext, $type)) {
                            if (strpos($file->getFilename(), $file_ext, 1)) {
                                if ($file_path) {
                                    $data[] = $file->getPathName();
                                } else {
                                    $data[] = $file->getFilename();
                                }
                            }
                        }
                    } else {
                        if (strpos($file->getFilename(), $type, 1)) {
                            if ($file_path) {
                                $data[] = $file->getPathName();
                            } else {
                                $data[] = $file->getFilename();
                            }
                        }
                    }
                } else {
                    if ($file->getFilename() !== '.' && $file->getFilename() !== '..') {
                        if ($file_path) {
                            $data[] = $file->getPathName();
                        } else {
                            $data[] = $file->getFilename();
                        }
                    }
                }
            }

            return $data;
        } else {
            return false;
        }
    }

    /**
     * Fetch the content from a file or URL.
     *
     * echo Filesystem::getFileContent('filename.txt');
     *
     * @param  string  $filename The file name
     * @return mixed
     */
    public static function getFileContent(string $filename)
    {
        if (Filesystem::fileExists($filename)) {
            return file_get_contents($filename);
        }
    }

    /**
     * Writes a string to a file.
     *
     * Filesystem::setFileContent('filename.txt', 'Content ...');
     *
     * @param  string  $filename    The path of the file.
     * @param  string  $content     The content that should be written.
     * @param  bool    $create_file Should the file be created if it doesn't exists?
     * @param  bool    $append      Should the content be appended if the file already exists?
     * @param  int     $chmod       Mode that should be applied on the file.
     * @return bool
     */
    public static function setFileContent(string $filename, string $content, bool $create_file = true, bool $append = false, $chmod = 0666) : bool
    {
        // File may not be created, but it doesn't exist either
        if (! $create_file && Filesystem::fileExists($filename)) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' doesn't exist", array(__METHOD__)));
        }

        // Create directory recursively if needed
        Filesystem::createDir(dirname($filename));

        // Create file & open for writing
        $handler = ($append) ? @fopen($filename, 'a') : @fopen($filename, 'w');

        // Something went wrong
        if ($handler === false) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' could not be created. Check if PHP has enough permissions.", array(__METHOD__)));
        }

        // Store error reporting level
        $level = error_reporting();

        // Disable errors
        error_reporting(0);

        // Write to file
        $write = fwrite($handler, $content);

        // Validate write
        if ($write === false) {
            throw new RuntimeException(vsprintf("%s(): The file '{$filename}' could not be created. Check if PHP has enough permissions.", array(__METHOD__)));
        }

        // Close the file
        fclose($handler);

        // Chmod file
        chmod($filename, $chmod);

        // Restore error reporting level
        error_reporting($level);

        // Return
        return true;
    }

    /**
     * Get time(in Unix timestamp) the file was last changed
     *
     * echo Filesystem::getFileLastChange('filename.txt');
     *
     * @param  string  $filename The file name
     * @return mixed
     */
    public static function getFileLastChange(string $filename)
    {
        // If file exists return filemtime
        if (Filesystem::fileExists($filename)) {
            return filemtime($filename);
        }

        // Return
        return false;
    }

    /**
     * Get last access time
     *
     * echo Filesystem::getFileLastAccess('filename.txt');
     *
     * @param  string  $filename The file name
     * @return mixed
     */
    public static function getFileLastAccess(string $filename)
    {
        // If file exists return fileatime
        if (Filesystem::fileExists($filename)) {
            return fileatime($filename);
        }

        // Return
        return false;
    }

    /**
     * Returns the mime type of a file. Returns false if the mime type is not found.
     *
     * echo Filesystem::getFileMimeType('filename.txt');
     *
     * @param  string  $file  Full path to the file
     * @param  bool    $guess Set to false to disable mime type guessing
     * @return mixed
     */
    public static function getFileMimeType(string $file, bool $guess = true)
    {
        // Get mime using the file information functions
        if (function_exists('finfo_open')) {
            $info = finfo_open(FILEINFO_MIME_TYPE);

            $mime = finfo_file($info, $file);

            finfo_close($info);

            return $mime;
        } else {

            // Just guess mime by using the file extension
            if ($guess === true) {
                $mime_types = File::$mime_types;

                $extension = pathinfo($file, PATHINFO_EXTENSION);

                return isset($mime_types[$extension]) ? $mime_types[$extension] : false;
            } else {
                return false;
            }
        }
    }

    /**
     * Forces a file to be downloaded.
     *
     * Filesystem::downloadFile('filename.txt');
     *
     * @param string  $file         Full path to file
     * @param string  $content_type Content type of the file
     * @param string  $filename     Filename of the download
     * @param int     $kbps         Max download speed in KiB/s
     */
    public static function downloadFile(string $file, $content_type = null, $filename = null, int $kbps = 0)
    {
        // Redefine vars
        $content_type = ($content_type === null) ? null : (string) $content_type;
        $filename     = ($filename === null) ? null : (string) $filename;

        // Check that the file exists and that its readable
        if (file_exists($file) === false || is_readable($file) === false) {
            throw new RuntimeException(vsprintf("%s(): Failed to open stream.", array(__METHOD__)));
        }

        // Empty output buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Send headers
        if ($content_type === null) {
            $content_type = Filesystem::getFileMimeType($file);
        }

        if ($filename === null) {
            $filename = basename($file);
        }

        header('Content-type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file));

        // Read file and write it to the output
        @set_time_limit(0);

        if ($kbps === 0) {
            readfile($file);
        } else {
            $handle = fopen($file, 'r');

            while (! feof($handle) && !connection_aborted()) {
                $s = microtime(true);

                echo fread($handle, round($kbps * 1024));

                if (($wait = 1e6 - (microtime(true) - $s)) > 0) {
                    usleep($wait);
                }
            }

            fclose($handle);
        }

        exit();
    }

    /**
     * Display a file in the browser.
     *
     * Filesystem::displayFile('filename.txt');
     *
     * @param string $file         Full path to file
     * @param string $content_type Content type of the file
     * @param string $filename     Filename of the download
     */
    public static function displayFile(string $file, $content_type = null, $filename = null)
    {
        // Redefine vars
        $content_type = ($content_type === null) ? null : (string) $content_type;
        $filename     = ($filename === null) ? null : (string) $filename;

        // Check that the file exists and that its readable
        if (file_exists($file) === false || is_readable($file) === false) {
            throw new RuntimeException(vsprintf("%s(): Failed to open stream.", array(__METHOD__)));
        }

        // Empty output buffers
        while (ob_get_level() > 0) {
            ob_end_clean();
        }

        // Send headers
        if ($content_type === null) {
            $content_type = Filesystem::getFileMimeType($file);
        }

        if ($filename === null) {
            $filename = basename($file);
        }

        header('Content-type: ' . $content_type);
        header('Content-Disposition: inline; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file));

        // Read file and write to output
        readfile($file);

        exit();
    }

    /**
     * Tests whether a file is writable for anyone.
     *
     * if (Filesystem::isFileWritable('filename.txt')) {
     *     // do something...
     * }
     *
     * @param  string  $file File to check
     * @return bool
     */
    public static function isFileWritable(string $file) : bool
    {
        // Is file exists ?
        if (! file_exists($file)) {
            throw new RuntimeException(vsprintf("%s(): The file '{$file}' doesn't exist", array(__METHOD__)));
        }

        // Gets file permissions
        $perms = fileperms($file);

        // Is writable ?
        if (is_writable($file) || ($perms & 0x0080) || ($perms & 0x0010) || ($perms & 0x0002)) {
            return true;
        }
    }

    /**
     * Creates a directory
     *
     * Filesystem::createDir('folder1');
     *
     * @param  string  $dir   Name of directory to create
     * @param  int     $chmod Chmod
     * @return bool
     */
    public static function createDir(string $dir, $chmod = 0775) : bool
    {
        // Create new dir if $dir !exists
        return (! Filesystem::dirExists($dir)) ? @mkdir($dir, $chmod, true) : true;
    }

    /**
     * Checks if this directory exists.
     *
     * if (Filesystem::dirExists('folder1')) {
     *     // Do something...
     * }
     *
     * @param  string  $dir Full path of the directory to check.
     * @return bool
     */
    public static function dirExists(string $dir) : bool
    {
        // Directory exists
        if (file_exists($dir) && is_dir($dir)) {
            return true;
        }

        // Doesn't exist
        return false;
    }


    /**
     * Check dir permission
     *
     * echo Filesystem::checkDirPerm('folder1');
     *
     * @param  string $dir Directory to check
     * @return string
     */
    public static function checkDirPerm(string $dir) : string
    {
        // Clear stat cache
        clearstatcache();

        // Return perm
        return substr(sprintf('%o', fileperms($dir)), -4);
    }


    /**
     * Delete directory
     *
     * Filesystem::deleteDir('folder1');
     *
     * @param string $dir Name of directory to delete
     */
    public static function deleteDir(string $dir)
    {
        // Delete dir
        if (is_dir($dir)) {
            $ob = scandir($dir);
            foreach ($ob as $o) {
                if ($o != '.' && $o != '..') {
                    if (filetype($dir.'/'.$o) == 'dir') {
                        Filesystem::deleteDir($dir.'/'.$o);
                    } else {
                        unlink($dir.'/'.$o);
                    }
                }
            }
        }
        reset($ob);
        rmdir($dir);
    }


    /**
     * Get list of directories
     *
     * $dirs = Filesystem::getDirList('folders');
     *
     * @param string $dir Directory
     */
    public static function getDirList(string $dir)
    {
        // Scan dir
        if (is_dir($dir) && $dh = opendir($dir)) {
            $f = array();
            while ($fn=readdir($dh)) {
                if ($fn != '.' && $fn != '..' && is_dir($dir.'/'.$fn)) {
                    $f[] = $fn;
                }
            }
            return $f;
        }
    }


    /**
     * Check if a directory is writable.
     *
     * if (Filesystem::isDirWritable('folder1')) {
     *     // Do something...
     * }
     *
     * @param  string $path The path to check.
     * @return bool
     */
    public static function isDirWritable(string $path) : bool
    {
        // Create temporary file
        $file = tempnam($path, 'writable');

        // File has been created
        if ($file !== false) {

            // Remove temporary file
            Filesystem::deleteFile($file);

            //  Writable
            return true;
        }

        // Else not writable
        return false;
    }


    /**
     * Get directory size.
     *
     * echo Filesystem::getDirSize('folder1');
     *
     * @param  string  $path The path to directory.
     * @return int
     */
    public static function getDirSize(string $path) : int
    {

        $total_size = 0;
        $files = scandir($path);
        $clean_path = rtrim($path, '/') . '/';

        foreach ($files as $t) {
            if ($t <> "." && $t <> "..") {
                $current_file = $clean_path . $t;
                if (is_dir($current_file)) {
                    $total_size += Filesystem::getDirSize($current_file);
                } else {
                    $total_size += filesize($current_file);
                }
            }
        }

        // Return total size
        return $total_size;
    }
}
