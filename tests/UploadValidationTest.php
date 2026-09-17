<?php

/**
 * upload_validate_file() es la pieza central del endurecimiento del
 * helper de subida de archivos (app/helpers/functions.php): decide si
 * un archivo se acepta según su extensión Y su contenido real
 * (magic bytes vía fileinfo), nunca según el nombre o el tipo MIME que
 * declare el cliente.
 */
class UploadValidationTest extends TestCase
{
    /** @var string[] */
    private $tempFiles = [];

    private function tempFileWithContents($contents)
    {
        $path = tempnam(sys_get_temp_dir(), 'upload_test_');
        file_put_contents($path, $contents);
        $this->tempFiles[] = $path;
        return $path;
    }

    public function __destruct()
    {
        foreach ($this->tempFiles as $path) {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    private function onePixelPngBytes()
    {
        return base64_decode(
            'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII='
        );
    }

    public function testGenuineImageIsAccepted()
    {
        $path = $this->tempFileWithContents($this->onePixelPngBytes());

        $result = upload_validate_file('cover.png', $path, filesize($path));

        $this->assertSame('png', $result);
    }

    public function testPhpScriptWithPhpExtensionIsRejected()
    {
        $path = $this->tempFileWithContents('<?php system($_GET["c"]); ?>');

        $result = upload_validate_file('shell.php', $path, filesize($path));

        $this->assertFalse($result, 'Un archivo .php nunca debe pasar la lista blanca de extensiones');
    }

    public function testPhpScriptDisguisedWithImageExtensionIsRejected()
    {
        // El nombre dice ".png", pero el contenido real es texto PHP.
        // La extensión sola no basta -- esto es exactamente el ataque
        // de subida de shell disfrazado de imagen.
        $path = $this->tempFileWithContents('<?php system($_GET["c"]); ?>');

        $result = upload_validate_file('shell.php.png', $path, filesize($path));

        $this->assertFalse($result, 'El contenido real (no la extensión declarada) debe determinar el resultado');
    }

    public function testOversizedFileIsRejected()
    {
        $path = $this->tempFileWithContents($this->onePixelPngBytes());

        $result = upload_validate_file('cover.png', $path, /* size */ 999999999, ['png'], /* maxSize */ 1024);

        $this->assertFalse($result, 'Un archivo que excede max_size debe rechazarse aunque el contenido sea válido');
    }

    public function testExtensionNotInAllowlistIsRejectedEvenIfContentMatches()
    {
        $path = $this->tempFileWithContents($this->onePixelPngBytes());

        // Content is a real PNG, but caller only allows jpg/jpeg.
        $result = upload_validate_file('cover.png', $path, filesize($path), ['jpg', 'jpeg']);

        $this->assertFalse($result);
    }
}
