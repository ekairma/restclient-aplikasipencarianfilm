<?php
use PHPUnit\Framework\TestCase;

class FileTypeTest extends TestCase
{
    private $projectFiles = [
        'index.php',
        'laman-judulfilm.php',
        'laman-tahunfilm.php',
        'laman-typefilm.php',
    ];

    /**
     * Test 1: Pastikan file yang dibutuhkan ada
     */
    public function test_files_exist()
    {
        foreach ($this->projectFiles as $file) {
            $this->assertFileExists($file, "File $file tidak ditemukan!");
        }
    }

    /**
     * Test 2: Pastikan file PHP mengandung tag PHP
     */
    public function test_php_files_contain_php_code()
    {
        foreach ($this->projectFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $content = file_get_contents($file);
                $this->assertStringContainsString('<?php', $content, "File $file tidak mengandung kode PHP!");
            }
        }
    }

    /**
     * Test 3: Pastikan file PHP berisi elemen HTML valid
     */
    public function test_html_files_contain_html_tags()
    {
        foreach ($this->projectFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $content = file_get_contents($file);

                $this->assertMatchesRegularExpression(
                    '/<html|<head|<body|<div|<p|<span/i',
                    $content,
                    "File $file bukan HTML yang valid!"
                );
            }
        }
    }

    /**
     * Test 4: Pastikan tidak ada error sintaks di file PHP
     */
    public function test_no_syntax_errors_in_php_files()
    {
        foreach ($this->projectFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $output = null;
                $resultCode = null;

                exec("php -l $file", $output, $resultCode);

                $this->assertSame(
                    0,
                    $resultCode,
                    "File $file memiliki error sintaks:\n" . implode("\n", $output)
                );
            }
        }
    }

    /**
     * Test 5: Pastikan tidak ada trailing whitespace
     */
    public function test_php_files_have_no_trailing_whitespace()
    {
        foreach ($this->projectFiles as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $lines = file($file, FILE_IGNORE_NEW_LINES);
                foreach ($lines as $lineNumber => $line) {
                    $this->assertDoesNotMatchRegularExpression(
                        '/\s+$/',
                        $line,
                        "File $file memiliki trailing whitespace di baris " . ($lineNumber + 1)
                    );
                }
            }
        }
    }

    /**
 * Test 6: Pastikan API Key dapat dibaca dari .env
 */
public function test_api_key_can_be_loaded()
{
    $envFile = __DIR__ . '/../.env';

    if (!file_exists($envFile)) {
        $this->markTestSkipped('.env tidak tersedia di environment ini');
    }

    $env = parse_ini_file($envFile);

    $this->assertArrayHasKey(
        'APIKEY',
        $env,
        'APIKEY tidak ditemukan di file .env'
    );

    $this->assertNotEmpty(
        $env['APIKEY'],
        'APIKEY di .env kosong'
    );
}


}
