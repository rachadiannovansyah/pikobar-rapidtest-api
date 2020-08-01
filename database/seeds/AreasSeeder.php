<?php

use Box\Spout\Reader\Common\Creator\ReaderEntityFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::disableQueryLog();

        $path = __DIR__ . '/csv/areas-20200801.csv';

        $reader = ReaderEntityFactory::createCSVReader();
        $reader->setFieldDelimiter(';');
        $reader->open($path);

        foreach ($reader->getSheetIterator() as $sheet) {
            $n = 0;
            foreach ($sheet->getRowIterator() as $row) {
                if ($n === 0) {
                    $n++;
                    continue;
                }

                $cells = $row->getCells();

                DB::table('areas')->insert([
                    'depth' => trim($cells[0]),
                    'name' => trim($cells[1]),
                    'parent_code_kemendagri' => !empty(trim($cells[2])) ? trim($cells[2]) : null,
                    'code_kemendagri' => trim($cells[3]),
                    'created_at' => '2020-08-01 00:00:00',
                    'updated_at' => '2020-08-01 00:00:00',
                ]);
            }
        }

        $reader->close();
    }
}
