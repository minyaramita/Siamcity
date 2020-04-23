<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ClaimController extends Controller
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
        namelists.year AS namelistYear 
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        GROUP BY
        namelists.year');

        //query จำนวนเคลม สถานะรอตรวจสอบเอกสาร
        $results2 = DB::select('SELECT
        COUNT(claims.id) AS countClaim01
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        WHERE claims.status_id = 1
        GROUP BY
        namelists.year');

        //query จำนวนเคลม สถานะรอการพิจารณาอนุมัติเคลม
        $results3 = DB::select('SELECT
        COUNT(claims.id) AS countClaim02
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        WHERE claims.status_id = 2
        GROUP BY
        namelists.year');

        //query จำนวนเคลม สถานะอนุมัติจ่ายเคลม
        $results4 = DB::select('SELECT
        COUNT(claims.id) AS countClaim03
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        WHERE claims.status_id = 3
        GROUP BY
        namelists.year');

        //query จำนวนเคลม สถานะไม่อนุมัติจ่ายเคลม
        $results5 = DB::select('SELECT
        COUNT(claims.id) AS countClaim04
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        WHERE claims.status_id = 4
        GROUP BY
        namelists.year');

        $namelistYear   = [];
        foreach ($results1 as $r1) {
            $namelistYear  [] = (array) $r1->namelistYear;
        }
         $countClaim01  = [];
        foreach ($results2 as $r2) {
            $countClaim01 [] = (array) $r2->countClaim01;
        }
         $countClaim02  = [];
        foreach ($results3 as $r3) {
            $countClaim02 [] = (array) $r3->countClaim02;
        }
         $countClaim03  = [];
        foreach ($results4 as $r4) {
            $countClaim03 [] = (array) $r4->countClaim03;
        }
        $countClaim04  = [];
        foreach ($results5 as $r5) {
            $countClaim04 [] = (array) $r5->countClaim04;
        }

        
        
        $chart1 = \Chart::title([
            'text' => 'รายงานจำนวนการเคลมประกันแบ่งตามสถานะการเคลมประกัน',
        ])
        ->chart([
            'type'     => 'column', // pie , column ect
            'renderTo' => 'chart1', // render the chart into your div with id  
            'borderWidth' => 1,
            'borderRadius' => 15,
            'style' => [
                'fontFamily' => 'Tahoma',
            ],                
        ])
        ->subtitle([
            'text' => 'ประกันอุบัติเหตุนักเรียน โครงการโรงเรียนอุ่นใจ',
        ])
        ->credits([
            'enabled' => false,
        ])
        ->colors([
            '#b38fe9',
        ])
        ->xaxis([
            'categories' => $namelistYear,
            'labels'     => [
                'align'     => 'center',
                //'formatter' => 'startJs:function(){return this.value}:endJs', 
                // use 'startJs:yourjavasscripthere:endJs'
            ],
        ])
        ->yaxis([
            'tickInterval' => 1,
            'title' => ([
                'text'  =>'Claim Quantity',
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
                    'name'  => 'รอตรวจสอบเอกสาร',
                    'data'  => $countClaim01,
                    'color' => '#eca829',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ],
                ],
                [
                    'name'  => 'รอการพิจารณาอนุมัติเคลม',
                    'data'  => $countClaim02,
                    'color' => '#5bc0de',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ],
                ],
                [
                    'name'  => 'อนุมัติจ่ายเคลม',
                    'data'  => $countClaim03,
                    'color' => '#5cb85c',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ],
                ],
                [
                    'name'  => 'ไม่อนุมัติจ่ายเคลม',
                    'data'  => $countClaim04,
                    'color' => '#d9534f',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ],
                ],
            ]
        )
        ->display();
         return view('chartClaim', [
            'chart1' => $chart1,
        ]);
    }
}