<?php
require('mem_image.php'); //get the memImage clas file
require('connection.php'); //get the connection data of mysql server
?>


<html>
<head>
    <title>PHPScript</title>
</head>

<body>
<!-- -------------FOREM START-------------------->
        <form method="post" enctype="multipart/form-data">
            <h1>Image Gallery</h1>
            <input type="file" name="image" accept=".jpg" >
            <input type="submit" name="submit" value="Upload">
            <input type="submit" name="disp" value="Display Data">
            <input type="submit" name="pdf" value="View PDF">
        </form>
    <hr>
    <br>

<!-- -------------FOREM END-------------------->

<?PHP
//---------------------------------------------------------------------------------------------------------------------------------------------------
        //-----------functionallity on submit button clicked------

        if(isset($_POST['submit']))     //if submit button clicked
        {
            if(getimagesize($_FILES['image']['tmp_name']) == FALSE) //if input type file has no 'image' file
            {
                echo 'Please select image to upload'; //display message there is no image
            }
            else                            //if input type file has 'image' file then
            {
                $name = addslashes($_FILES['image']['name']);  //store the image name in vaiable
                $image = addslashes (file_get_contents($_FILES['image']['tmp_name'])); //storing the image file in variable

                uploadimage($name,$image); //call the function upload image

            }
        }

        //----------------functionallity on display data button clicked----

        if(isset($_POST['disp']))
        {

            //call the funtion
            display_data();
        }

        //-----------------functionallity on display data in pdf button clicked--

        if(isset($_POST['pdf']))
        {
            pdf_view();
        }

//--------------------------------------------------------------------------------------------------------------------------------------------





//----------- this function called when submit button is clicked ant the inut type file has browsed 'image' file
        function uploadimage($name,$image)
        {
            global $connection;

            $dataTime = date("Y-m-d");
            $sql = "insert into uploadimg(name,file,created_at)values('$name','$image','$dataTime');";

            $result = mysqli_query($connection,$sql);

            if($result)
            {   echo 'File uploaded Successfully';  }
            else
            {   echo 'File not Uploaded';   }

        }
//----------end of uploadimg function--------------------------------







//----------- this function called when display_data button is clicked to view the databse data.---------

    function display_data()
    {
        global $connection;

        $sql = "SELECT * FROM uploadimg";
        $result = mysqli_query($connection,$sql);
        ?>
        <center>
            <table border="2" style="width:1000px;" id="tbl_data">

                <thead>
                    <tr> <h2>Uploaded Image Details </h2>  </tr>
                    <tr>
                        <th>ID</th>
                        <th>NAME</th>
                        <th>File</th>
                        <th>Date</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                            if(mysqli_num_rows($result)== 0 )
                            {
                                    echo "<script>alert('No rows returned');</script>";
                            }
                            else
                            {
                                while($row = mysqli_fetch_assoc($result))
                                {
                                    echo "<tr align=center>";
                                    echo "<td>{$row['id']}</td>";
                                    echo "<td>{$row['name']}</td>";
                                    echo '<td><img style="height:200px;width:300px;" src="data:image/jpg;base64,' . base64_encode( $row['file'] ) . '" /></td>';
                                    echo"<td>{$row['created_at']}</td>";
                                    echo"</tr>";
                                }
                                mysqli_close($connection);

                            }
                    ?>
                </tbody>
            </table>
        </center>
        <?php
    }
//----------------------------end of the function-----------------------









//----------- this function called when View PDF button is clicked to view the databse data in pdf file.---------
    function pdf_view()
    {

        global $connection;


        $pdf=new PDF_MemImage();                                   //new instance to generate new fpdf
        $pdf->AddPage();                                           //add the first page in pdf
        $pdf->SetFont('Arial','B',16);                             //set the font family,size,bold
        $pdf->Cell(40,10,'All Uploaded Images Displayed Below:'); //cell to set the size of cell and the statement to display in it.

        $sql = "SELECT file FROM uploadimg";
        $result = mysqli_query($connection,$sql);

        if(mysqli_num_rows($result)== 0 )
        {
            $pdf->Cell(40,10,'There is not image in databse to placed'); //if no records found this msg will will show on pdf file

        }
        else
        {
            //set the image height and width
            $image_height = 80; $image_width = 80;

            //set the image first co-ordinates to show the image
            $start_x = 10;  $start_y = 10;

            //consition to show the data till the last record displayed
            while($row = mysqli_fetch_assoc($result))
            {
                $pic = $row['file'];    //get the individal file of each record

                $pdf->Cell(80, 80, $pdf->MemImage($pic,$start_x, $start_y+20 ,$image_height ,$image_width ),0, 0, 'L', false ); //store the image file in cell with size of 80,80

                $pdf->SetX(100); //reset the x co-ordinate to 100 for seconf image.

                if($start_x >80)
                {
                    $pdf->Ln(100); //break to new line if the addition of width(x co-ordinate) of image is more the 80
                }
                if($start_y > 100)
                {
                    $pdf->AddPage(); //break to new page if the addition of height (y co-ordinate) of image is more the 100
                }

                //get the current co-ordinates of image
                $start_x = $pdf->GetX(); $start_y = $pdf->GetY();
            }
            mysqli_close($connection);
        }
        $pdf->Output();
    }
//----------------------------end of the function-----------------------


?>
</body>
</html>