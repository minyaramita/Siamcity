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
        //query สถานะเคลม
        $results1 = DB::select('SELECT
        statuses.name_en AS claimstatus 
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        GROUP BY
        statuses.name_en');

        //query จำนวนเคลม 2017
        $results2 = DB::select('SELECT
        COUNT(claims.id) AS countclaim2017
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        WHERE namelists.year = "2017"
        GROUP BY
        statuses.name_en');

        //query จำนวนเคลม 2018
        $results3 = DB::select('SELECT
        COUNT(claims.id) AS countclaim2018
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        WHERE namelists.year = "2018"
        GROUP BY
        statuses.name_en');

        //query จำนวนเคลม 2019
        $results4 = DB::select('SELECT
        COUNT(claims.id) AS countclaim2019
        FROM
        claims
        INNER JOIN statuses ON claims.status_id = statuses.id
        INNER JOIN insurers ON claims.ins_id = insurers.id
        INNER JOIN namelists ON insurers.namelist_id = namelists.id
        WHERE namelists.year = "2019"
        GROUP BY
        statuses.name_en');

        $claimstatus  = [];
        foreach ($results1 as $r1) {
            $claimstatus [] = (array) $r1->claimstatus;
        }

        $countclaim2017  = [];
        foreach ($results2 as $r2) {
            $countclaim2017 [] = (array) $r2->countclaim2017;
        }

        $countclaim2018  = [];
        foreach ($results3 as $r3) {
            $countclaim2018 [] = (array) $r3->countclaim2018;
        }

        $countclaim2019  = [];
        foreach ($results4 as $r4) {
            $countclaim2019 [] = (array) $r4->countclaim2019;
        }
        
        $chart1 = \Chart::title([
            'text' => 'รายงานจำนวนการเคลมประกันแบ่งตามสถานะการเคลมประกัน',
        ])
        ->chart([
            'type'     => 'column', // pie , column ect
            'renderTo' => 'chart1', // render the chart into your div with id  
            'borderWidth' => 1,
            'borderRadius' => 15,              
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
            'categories' => $claimstatus,
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
                    'name'  => 'ปีการศึกษา 2017',
                    'data'  => $countclaim2017,
                    'color' => '#b38fe9',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ],
                ],
                [
                    'name'  => 'ปีการศึกษา 2018',
                    'data'  => $countclaim2018,
                    'color' => '#f66D9b',
                    'dataLabels'  => [
                        'enabled' => true,
                        'color'  => 'rgb(133, 127, 127)',
                    ],
                ],
                [
                    'name'  => ' ปีการศึกษา 2019',
                    'data'  => $countclaim2019,
                    'color' => '#8fb3d1',
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
