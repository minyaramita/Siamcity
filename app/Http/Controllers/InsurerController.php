<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class InsurerController extends Controller
{
 /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function chartreport(){
        //query ปีการศึกษา
        $results1 = DB::select('SELECT
        namelists.year AS namelistsYear
        FROM
        insurers
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        GROUP BY
        namelists.year');

        //query จำนวนผู้ทำประกัน 
        $results2 = DB::select('SELECT
        COUNT(insurers.id) AS countInsurer
        FROM
        insurers
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        GROUP BY
        namelists.year');

        //query จำนวนโรงเรียน
        $results3 = DB::select('SELECT
        COUNT(schools.id) AS countSchool
        FROM
        schools
        INNER JOIN namelists ON namelists.school_id = schools.id
        GROUP BY
        namelists.year');

        $namelistsYear  = [];
        foreach ($results1 as $r1) {
            $namelistsYear [] = (array) $r1->namelistsYear;
        }

        $countInsurer  = [];
        foreach ($results2 as $r2) {
            $countInsurer [] = (array) $r2->countInsurer;
        }

        $countSchool  = [];
        foreach ($results3 as $r3) {
            $countSchool [] = (array) $r3->countSchool;
        }

        $chart2 = \Chart::title([
            'text' => 'รายงานจำนวนผู้ทำประกันอุบัติเหตุนักเรียนในแต่ละปีการศึกษา',
        ])
        ->chart([
            'type'     => 'column', // pie , column ect
            'renderTo' => 'chart2', // render the chart into your div with id  
            'borderWidth' => 1,
            'borderRadius' => 15,  
            'style' => [
                'fontFamily' => 'Tahoma',
            ],              
        ])
        ->subtitle([
            'text' => 'โครงการโรงเรียนอุ่นใจ',
        ])
        ->credits([
            'enabled' => false,
        ])
        ->colors([
            '#b38fe9',
        ])
        ->xaxis([
            'categories' => $namelistsYear,
            'labels'     => [
                'align'     => 'center',
                //'formatter' => 'startJs:function(){return this.value}:endJs', 
                // use 'startJs:yourjavasscripthere:endJs'
            ],
        ])
        ->yaxis([
            'tickInterval' => 1,
            'title' => ([
                'text'  =>'Values',
            ])
        ])
        ->legend([
            'layout'        => 'vertikal',
            'align'         => 'right',
            'verticalAlign' => 'middle',
        ])
        ->series(
            [
                [
                    'name'  => 'จำนวนผู้ทำประกัน',
                    'data'  => $countInsurer,
                    'color' => '#8db7da',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ]
                ],
                [
                    'name'  => 'จำนวนสถานศึกษา',
                    'data'  => $countSchool,
                    'color' => '#9d7bd1',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ]
                ]
            ]            
        )
        ->display();
        return view('chartInsurer', [
            'chart2' => $chart2,
        ]);
    }
}

