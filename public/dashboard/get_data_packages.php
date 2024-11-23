<?php
require_once('dbcon.php');
require_once("./endpoints/DigitalVendorzApi.php");

$network = mysqli_real_escape_string($con, $_REQUEST['network']);

// $thandler = new DigitalVendorzApi();
// $networkMap = ['2'=>"MTN",'1'=>"AIRTEL",'3'=>"GLO","4"=>"ETISALAT"];
// $network = $networkMap[$network];
// $packages = $thandler->getDataPackages($network);

$packages = [
    //mtn
    "2"=>'[
        {
            "id": 1,
            "label": "500MB 30 Days",
            "value": "500MB",
            "description": "30 Days",
            "price": "189.00",
            "discount": "0.00",
            "status": 1,
            "date_added": "2021-06-03 17:51:19",
            "date_updated": "2021-06-04 17:51:19"
        },
        {
            "id": 2,
            "label": "1GB 30 Days",
            "value": "1GB",
            "description": "30 Days",
            "price": "289.00",
            "discount": "0.00",
            "status": 1,
            "date_added": "2021-06-03 17:51:19",
            "date_updated": "2021-06-04 17:51:19"
        },
        {
            "id": 3,
            "label": "2GB 30 Days",
            "value": "2GB",
            "description": "30 Days",
            "price": "578.00",
            "discount": "0.00",
            "status": 1,
            "date_added": "2021-06-03 17:51:19",
            "date_updated": "2021-06-04 17:51:19"
        },
        {
            "id": 4,
            "label": "3GB 30 Days",
            "value": "3GB",
            "description": "3GB Monthly SME",
            "price": "867.00",
            "discount": "0.00",
            "status": 1,
            "date_added": "2021-07-10 05:27:08",
            "date_updated": "2021-07-10 05:27:08"
        },
        {
            "id": 5,
            "label": "5GB 30 Days SME",
            "value": "5GB",
            "description": "5GB Monthly SME",
            "price": "1445.00",
            "discount": "0.00",
            "status": 1,
            "date_added": "2021-07-10 05:27:08",
            "date_updated": "2021-07-10 05:27:08"
        },
        {
            "id": 6,
            "label": "10GB 30 Days SME",
            "value": "10GB",
            "description": "10GB 30 Days SME",
            "price": "2890.00",
            "discount": "0.00",
            "status": 1,
            "date_added": "2021-06-03 17:51:19",
            "date_updated": "2021-06-04 17:51:19"
        }
    ]',
    //Airtel
    "1"=>'[
        {
            "id": 1,
            "label": "25MB-1Day",
            "value": "49.99",
            "description": "25MB-1Day",
            "price": "50",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 2,
            "label": "75MB-1Day",
            "value": "99",
            "description": "75MB-1Day",
            "price": "100",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 3,
            "label": "200MB-3Days",
            "value": "199.03",
            "description": "200MB-3Days",
            "price": "200",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 4,
            "label": "350MB-7Days",
            "value": "299.02",
            "description": "350MB-7Days",
            "price": "300",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 5,
            "label": "750MB-14Days",
            "value": "499",
            "description": "750MB-14Days",
            "price": "500",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 6,
            "label": "1.5GB-30Days",
            "value": "999",
            "description": "1.5GB-30Days",
            "price": "1000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 7,
            "label": "3GB-30Days",
            "value": "1499.01",
            "description": "3GB-30Days",
            "price": "1500",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 8,
            "label": "4.5GB-30Days",
            "value": "1999",
            "description": "4.5GB-30Days",
            "price": "2000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 9,
            "label": "6GB-30Days",
            "value": "2499.01",
            "description": "6GB-30Days",
            "price": "2500",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 10,
            "label": "10GB -30Days",
            "value": "2999.02",
            "description": "10GB -30Days",
            "price": "3000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 11,
            "label": "11GB-30Days",
            "value": "3999.01",
            "description": "11GB-30Days",
            "price": "4000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 12,
            "label": "20GB-30Days",
            "value": "4999",
            "description": "20GB-30Days",
            "price": "5000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 13,
            "label": "40GB-30Days",
            "value": "9999",
            "description": "40GB-30Days",
            "price": "1000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 14,
            "label": "75GB-30Days",
            "value": "14999",
            "description": "75GB-30Days",
            "price": "15000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 15,
            "label": "120GB-30Days",
            "value": "19999.02",
            "description": "120GB-30Days",
            "price": "20000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 16,
            "label": "1GB-1Day",
            "value": "299.03",
            "description": "1GB-1Day",
            "price": "300",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 17,
            "label": "2GB-1Day",
            "value": "499.03",
            "description": "2GB-1Day",
            "price": "500",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 18,
            "label": "6GB-7Days",
            "value": "1499.03",
            "description": "6GB-7Days",
            "price": "1500",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        },
        {
            "id": 19,
            "label": "25GB-30Days",
            "value": "7999.02",
            "description": "25GB-30Days",
            "price": "8000",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 21:47:13",
            "date_updated": "2022-02-16 21:47:13"
        }
    ]',
    //glo
    "3"=>'[
            {
                "id": 1,
                "label": "50MB-24hrs",
                "value": "50",
                "description": "50MB-24hrs",
                "price": "50.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 2,
                "label": "150MB-24hrs",
                "value": "100",
                "description": "150MB-24hrs",
                "price": "100.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 3,
                "label": "350MB-48hrs",
                "value": "200",
                "description": "350MB-48hrs",
                "price": "200.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 4,
                "label": "1.31GB-14days",
                "value": "500",
                "description": "1.31GB-14days",
                "price": "500.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 5,
                "label": "2.9GB-30days",
                "value": "1000",
                "description": "2.9GB-30days",
                "price": "1000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 6,
                "label": "4.1GB-30days",
                "value": "1500",
                "description": "4.1GB-30days",
                "price": "1500.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 7,
                "label": "5.8GB-30days",
                "value": "2000",
                "description": "5.8GB-30days",
                "price": "2000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 8,
                "label": "7.7GB-30days",
                "value": "2500",
                "description": "7.7GB-30days",
                "price": "2500.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 9,
                "label": "11GB-30days",
                "value": "3000",
                "description": "11GB-30days",
                "price": "3000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 10,
                "label": "15GB-30days",
                "value": "4000",
                "description": "15GB-30days",
                "price": "4000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 11,
                "label": "22GB-30days",
                "value": "5000",
                "description": "22GB-30days",
                "price": "5000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 12,
                "label": "29.5GB-30days",
                "value": "8000",
                "description": "29.5GB-30days",
                "price": "8000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 13,
                "label": "50GB-30days",
                "value": "10000",
                "description": "50GB-30days",
                "price": "10000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 14,
                "label": "93GB-30days",
                "value": "15000",
                "description": "93GB-30days",
                "price": "15000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 15,
                "label": "119GB-30days",
                "value": "18000",
                "description": "119GB-30days",
                "price": "18000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 16,
                "label": "138GB-30days",
                "value": "20000",
                "description": "138GB-30days",
                "price": "20000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 17,
                "label": "225GB-30days",
                "value": "30000",
                "description": "225GB-30days",
                "price": "30000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 18,
                "label": "425GB-90days",
                "value": "50000",
                "description": "425GB-90days",
                "price": "50000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            },
            {
                "id": 19,
                "label": "1024GB-1year",
                "value": "100000",
                "description": "1024GB-1year",
                "price": "100000.00",
                "discount": null,
                "status": 1,
                "date_added": "2022-02-16 21:58:52",
                "date_updated": "2022-02-16 21:58:52"
            }
        ]',
    //9mobile
    "4"=>'[
        {
            "id": 1,
            "label": "25MB (1 Days)",
            "value": "50",
            "description": "25MB (1 Days)",
            "price": "50.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 2,
            "label": "100MB (1 Days)",
            "value": "100",
            "description": "100MB (1 Days)",
            "price": "100.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 3,
            "label": "650MB (1 Days)",
            "value": "200",
            "description": "650MB (1 Days)",
            "price": "200.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 4,
            "label": "1GB (1 Days)",
            "value": "300",
            "description": "1GB (1 Days)",
            "price": "300.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 5,
            "label": "500MB (30 Days)",
            "value": "500",
            "description": "500MB (30 Days)",
            "price": "500.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 6,
            "label": "1.5GB (30 Days)",
            "value": "1000",
            "description": "1.5GB (30 Days)",
            "price": "1000.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 7,
            "label": "2GB (30 Days)",
            "value": "1200",
            "description": "2GB (30 Days)",
            "price": "1200.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 8,
            "label": "7GB (7 Days)",
            "value": "1500",
            "description": "7GB (7 Days)",
            "price": "1500.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 9,
            "label": "4.5GB (30 Days)",
            "value": "2000",
            "description": "4.5GB (30 Days)",
            "price": "2000.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 10,
            "label": "11GB (30 Days)",
            "value": "4000",
            "description": "11GB (30 Days)",
            "price": "4000.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 11,
            "label": "15GB (30 Days)",
            "value": "5000",
            "description": "15GB (30 Days)",
            "price": "5000.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:04",
            "date_updated": "2022-02-16 22:01:04"
        },
        {
            "id": 12,
            "label": "40GB (30 Days)",
            "value": "10000",
            "description": "40GB (30 Days)",
            "price": "10000.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:05",
            "date_updated": "2022-02-16 22:01:05"
        },
        {
            "id": 13,
            "label": "75GB (30 Days)",
            "value": "15000",
            "description": "75GB (30 Days)",
            "price": "15000.00",
            "discount": null,
            "status": 1,
            "date_added": "2022-02-16 22:01:05",
            "date_updated": "2022-02-16 22:01:05"
        }
    ]'
];

echo $packages[$network];
