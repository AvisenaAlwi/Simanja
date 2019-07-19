<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class CreateFunction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('CREATE FUNCTION Convert_Month_to_Number(month varchar(255)) returns int
        BEGIN
            RETURN
                CASE month
                    WHEN "Januari" THEN 1
                    WHEN "Februari" THEN 2
                    WHEN "Maret" THEN 3
                    WHEN "April" THEN 4
                    WHEN "Mei" THEN 5
                    WHEN "Juni" THEN 6
                    WHEN "Juli" THEN 7
                    WHEN "Agustus" THEN 8
                    WHEN "September" THEN 9
                    WHEN "Oktober" THEN 10
                    WHEN "November" THEN 11
                    WHEN "Desember" THEN 12
                    ELSE 13
                END;
        END;');

        DB::unprepared('CREATE FUNCTION Is_In_Month_Range(
            month varchar(255),
            startMonth varchar(255),
            endMonth varchar(255)
        ) returns int
        BEGIN
            DECLARE year Year;
            SET year = YEAR(CURDATE());
            IF endMonth IS NULL THEN
                SET endMonth = startMonth;
            END IF;
            IF endYear IS NULL THEN
                SET endYear = startYear;
            END IF;
            SET month = Convert_Month_to_Number(month);
            SET startMonth = Convert_Month_to_Number(startMonth);
            SET endMonth = Convert_Month_to_Number(endMonth);
            RETURN (month >= startMonth && month <= endMonth && year >= startYear && year <= endYear);
        END;');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP FUNCTION IF EXISTS Convert_Month_to_Number;');
        DB::unprepared('DROP FUNCTION IF EXISTS Is_In_Month_Range;');
    }
}
