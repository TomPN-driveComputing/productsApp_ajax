<?php
    //Tom Price-Nicholson, 6/11/2017
    //Ajax calls for Products App

    $request = $_POST['request'];
    $productId = $_POST['productId'];
    
    $jsonval = new stdClass();
    
    require_once "sql_class.php";
    $sqlClass = new SQLClass;
    
    if ($request == 'getProducts')
    {
        $query = "SELECT * FROM PRODUCTS";
        $data = $sqlClass -> SQL_Read($query);
        
        $jsonval -> productData = $data;
    }
    else if ($request == 'getProductById')
    {
        $query = "SELECT * FROM Products WHERE prodID = $productId";
        $data = $sqlClass -> SQL_Read($query);
        
        $jsonval -> productData = $data[0];
    }
    else if ($request == 'getProductByName')
    {
        $productName = $_POST['productName'];
        
        $query = "SELECT * FROM Products WHERE prodName = '$productName'";
        $data = $sqlClass -> SQL_Read($query);
        
        $jsonval -> productData = $data[0];
    }
    else if ($request == 'buyProduct')
    {
        $qty = $_POST['qty'];
        $name = $_POST['name'];
        $address = $_POST['address'];
        $postcode = $_POST['postcode'];
        
        $query1 = "SELECT available, pending FROM Products WHERE prodID = $productId";
        $prodData = $sqlClass -> SQL_Read($query1);
        
        $availableUpdate = ($prodData[0]['available'] - $qty);
        $pendingUpdate = ($prodData[0]['pending'] + $qty);
        
        $query2 = "UPDATE Products SET available = $availableUpdate, pending = $pendingUpdate WHERE prodId = $productId";
        $data1 = $sqlClass -> SQL_Update($query2);
        
        $query3 = "INSERT INTO Orders (orderProductId, orderQty, buyerName, buyerAddress, buyerPostcode, orderStatus)"
                ."VALUES ('$productId','$qty','$name','$address','$postcode','Pending review')";
        $data2 = $sqlClass -> SQL_Update($query3);
    }
    
    echo json_encode($jsonval);
?>