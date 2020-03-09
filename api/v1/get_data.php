<?php
error_reporting(0);
date_default_timezone_set('Asia/Jakarta');
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET");
require 'koneksi.php';

$contentType = explode(';', $_SERVER['CONTENT_TYPE']);
$accessBearerToken = $_SERVER['HTTP_AUTHORIZATION'];
$rawBody = file_get_contents("php://input");
$data = array();

    $nih = substr($accessBearerToken, 6);
    $substrBasic = substr($accessBearerToken, 0, 5);

    if($substrBasic != "Basic")
    {
    	header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                'statusCode' => 401,
                'status'=> false,
                'Code'=>'01',
                'message' => 'Authentication token is required and has failed or has not yet been provided'
                ));
    	exit();
    }
    
    if($nih != "N2Q5NmY2OTc5MDMxOWNmNmM1ZmViMjU4NDllYjQ0ODU6MGFhYmZkYjEwN2EwYTBjYmI0YTVlYTk3MjQyOTZjZGM=")
    {
        header('HTTP/1.1 401 Unauthorized');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                'statusCode' => 401,
                'status'=> false,
                'Code'=>'01',
                'message' => 'Authentication token is required and has failed or has not yet been provided'
                ));
    	exit();
    }

    $getData = $conn->prepare("SELECT * FROM pkl ORDER BY id DESC");
    $getData->execute();
    
    if($getData->rowCount() <= 0)
    {
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                        'statusCode' => 200,
                        'status'=> false,
                        'Code'=>'01',
                        'message' => 'Terjadi masalah saat ambil data'
                        ));
        exit();
    }
    
    if($getData->rowCount() > 0)
    {
        $arraydata = array();
        $arrayprogress = array();
        foreach($getData as $rowData)
        {
            $namapkl = $rowData['nama_pkl'];
            $studikasus = $rowData['studi_kasus'];
            $kodepkl = $rowData['kode_pkl'];
            
             //
            $getProgress = $conn->prepare("SELECT * FROM mahasiswa_pkl WHERE kode_pkl=:kode_pkl ORDER BY id DESC");
            $getProgress->bindParam(':kode_pkl', $kodepkl);
            $getProgress->execute();
            
            foreach($getProgress as $rowProgress)
            {
            $progress = $rowProgress['progress'];
            $tanggal = $rowProgress['tanggal'];
            $kode_pkl = $rowProgress['kode_pkl'];
            $persen = $rowProgress['persen'];
            
            $arrayprogress2 = [
                        'progress'=>$progress,
                        'tanggal'=>$tanggal,
                        'kode_pkl'=>$kode_pkl,
                        'persen'=>$persen
                        ];
                        
            array_push($arrayprogress, $arrayprogress2);
            }
            
            $arraydata2 = [
                    'nama_pkl'=>$namapkl,
                    'studi_kasus'=>$studikasus,
                    'kode_pkl'=>$kodepkl,
                    'progress_data'=>$arrayprogress
                    ];
            
            array_push($arraydata, $arraydata2);
            $arrayprogress = [];
        }
        
        header('HTTP/1.1 200 OK');
        header('Content-Type: application/json; charset=UTF-8');
        echo json_encode(array(
                        'statusCode' => 200,
                        'status'=> true,
                        'Code'=>'00',
                        'message' => 'Get Data Sukses',
                        'data'=>
                            $arraydata
                            
                        ));
        exit();
    }
?>