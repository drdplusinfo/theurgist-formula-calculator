<?php
declare(strict_types=1);

namespace DrdPlus\Tests\Tables\Measurements\Partials;

use DrdPlus\Tables\Partials\AbstractFileTable;
use Granam\Tests\Tools\TestWithMockery;

final class AbstractFileTableTest extends TestWithMockery
{
    /**
     * @test
     */
    public function I_can_use_array_as_data_type()
    {
        $table = new TableWithArrayForTests();
        self::assertSame([
            'foo_FOO' => [
                'bar' => ['bar_BAR', 'Foo', 'Bar', 'Baz', 'Qux'],
                'baz' => 'baz_BAZ',
            ],
        ],
            $table->getIndexedValues()
        );
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\CanNotReadFile
     */
    public function I_am_stopped_if_datafile_has_not_been_read()
    {
        $table = new TableWithWrongFileReference();
        @$table->getIndexedValues();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\NoDataFetched
     */
    public function I_am_stopped_if_datafile_is_empty()
    {
        $table = new TableWithEmptyFile();
        $table->getIndexedValues();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\DataAreCorrupted
     */
    public function I_am_stopped_if_header_row_is_missing()
    {
        $table = new TableWithMissingHeaderRow();
        $table->getIndexedValues();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\DataAreCorrupted
     */
    public function I_am_stopped_if_header_column_is_missing()
    {
        $table = new TableWithMissingHeaderColumn();
        $table->getIndexedValues();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\DataAreCorrupted
     */
    public function I_am_stopped_if_header_value_is_invalid()
    {
        $table = new TableWithUnexpectedDataHeaderValue();
        $table->getIndexedValues();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\UnknownTypeForColumn
     */
    public function I_can_not_use_table_with_unknown_column_type()
    {
        $table = new TableWithUnknownColumnScalarType();
        $table->getIndexedValues();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\NoRowRequested
     */
    public function I_can_not_request_row_without_providing_indexes()
    {
        $table = new TableWithPublicHeaders();
        $table->getRow([]);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\RequiredRowNotFound
     */
    public function I_can_not_get_row_by_invalid_index()
    {
        $table = new TableWithPublicHeaders();
        $table->getRow(['non-existing index']);
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\RequiredColumnNotFound
     */
    public function I_can_not_get_value_by_invalid_indexes()
    {
        $table = new TableWithPublicHeaders();
        $table->getValue(['baz'], 'non-existing column index');
    }

    /**
     * @test
     */
    public function I_can_use_table_without_rows_header()
    {
        $table = new TableWithEmptyRowsHeader();
        self::assertSame([], $table->getHeader());
        self::assertSame([['foo' => 'baz', 'bar' => 'qux']], $table->getIndexedValues());
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\UnknownFetchedColumn
     */
    public function I_can_not_create_table_with_missing_expected_header_record()
    {
        $table = new TableWithMissingExpectedDataHeader();
        $table->getIndexedValues();
    }

    /**
     * @test
     * @expectedException \DrdPlus\Tables\Partials\Exceptions\ExpectedDataHeaderNamesToTypesAreEmpty
     */
    public function I_can_not_create_table_with_empty_expected_header_record()
    {
        $table = new TableWithEmptyExpectedDataHeader();
        $table->getIndexedValues();
    }
}

/** inner */
class TableWithWrongFileReference extends AbstractFileTable
{

    protected function getDataFileName(): string
    {
        return 'non existing filename';
    }

    protected function getRowsHeader(): array
    {
        return [];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [];
    }

    public function getHeader(): array
    {
        return [];
    }

}

class TableWithArrayForTests extends AbstractFileTable
{
    protected $dataFileName;

    public function __construct()
    {
        $this->dataFileName = $this->createDataFileName();
        file_put_contents($this->dataFileName, "foo,bar,baz\nfoo_FOO,bar_BAR;Foo;Bar;Baz;Qux,baz_BAZ");
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [
            'bar' => self::ARRAY,
            'baz' => self::STRING,
        ];
    }

    protected function getRowsHeader(): array
    {
        return ['foo'];
    }

    protected function createDataFileName()
    {
        return tempnam(sys_get_temp_dir(), preg_replace('~^.*[\\\](\w+)$~', '$1', __CLASS__));
    }

    public function __destruct()
    {
        if (file_exists($this->dataFileName)) {
            unlink($this->dataFileName);
        }
    }

    protected function getDataFileName(): string
    {
        return $this->dataFileName;
    }
}

class TableWithEmptyFile extends TableWithWrongFileReference
{
    protected $dataFileName;

    public function __construct()
    {
        $this->dataFileName = $this->createDataFileName();
        file_put_contents($this->dataFileName, '');
    }

    protected function createDataFileName()
    {
        return tempnam(sys_get_temp_dir(), preg_replace('~^.*[\\\](\w+)$~', '$1', __CLASS__));
    }

    public function __destruct()
    {
        if (file_exists($this->dataFileName)) {
            unlink($this->dataFileName);
        }
    }

    protected function getDataFileName(): string
    {
        return $this->dataFileName;
    }
}

class TableWithMissingHeaderRow extends TableWithEmptyFile
{

    protected function getDataFileName(): string
    {
        file_put_contents($this->dataFileName, 'foo');

        return $this->dataFileName;
    }

    protected function getRowsHeader(): array
    {
        return [999 => ['foo']];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [];
    }
}

class TableWithMissingHeaderColumn extends TableWithEmptyFile
{

    protected function getDataFileName(): string
    {
        file_put_contents($this->dataFileName, 'foo');

        return $this->dataFileName;
    }

    protected function getRowsHeader(): array
    {
        return [999 => 'foo'];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [];
    }
}

class TableWithUnexpectedDataHeaderValue extends TableWithEmptyFile
{

    protected function getDataFileName(): string
    {
        file_put_contents($this->dataFileName, 'invalid header');

        return $this->dataFileName;
    }

    protected function getRowsHeader(): array
    {
        return ['expected header'];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [];
    }
}

class TableWithPublicHeaders extends TableWithEmptyFile
{
    public function __construct()
    {
        parent::__construct();
        file_put_contents($this->dataFileName, implode(',', ['foo', 'bar']) . "\n" . implode(',', ['baz', 123]));
    }

    public function getRowsHeader(): array
    {
        return ['foo'];
    }

    public function getExpectedDataHeaderNamesToTypes(): array
    {
        return ['bar' => self::INTEGER];
    }
}

class TableWithUnknownColumnScalarType extends TableWithEmptyFile
{
    protected function getDataFileName(): string
    {
        file_put_contents($this->dataFileName, implode(',', ['foo', 'bar']) . "\n" . implode(',', ['baz', 'qux']));

        return $this->dataFileName;
    }

    protected function getRowsHeader(): array
    {
        return ['foo'];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return ['bar' => 'unknown type'];
    }
}

class TableWithEmptyRowsHeader extends TableWithEmptyFile
{
    protected function getDataFileName(): string
    {
        file_put_contents($this->dataFileName, implode(',', ['foo', 'bar']) . "\n" . implode(',', ['baz', 'qux']));

        return $this->dataFileName;
    }

    protected function getRowsHeader(): array
    {
        return [];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return ['foo' => self::STRING, 'bar' => self::STRING];
    }
}
class TableWithMissingExpectedDataHeader extends TableWithEmptyFile
{
    protected function getDataFileName(): string
    {
        file_put_contents($this->dataFileName, implode(',', ['foo', 'bar']) . "\n" . implode(',', ['baz', 'qux']));

        return $this->dataFileName;
    }

    protected function getRowsHeader(): array
    {
        return [];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return ['foo' => self::STRING /* bar is missing */];
    }
}
class TableWithEmptyExpectedDataHeader extends TableWithEmptyFile
{
    protected function getDataFileName(): string
    {
        file_put_contents($this->dataFileName, implode(',', ['foo', 'bar']) . "\n" . implode(',', ['baz', 'qux']));

        return $this->dataFileName;
    }

    protected function getRowsHeader(): array
    {
        return [];
    }

    protected function getExpectedDataHeaderNamesToTypes(): array
    {
        return [];
    }
}