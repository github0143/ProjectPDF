# ProjectPDF
PHP script for upload image in PDF  using FPDF

SETUP:

1) Copy all this files in your localohost new folder i.e.(xampp or wamp).

2) Create the database with the name of 'project_db' in phpmyadmin(myqsl).

3) After DB creation,now create the tabel with 4 columns.
	
	table name = 'uploadimg'

	column1 = id (with datatype:int & constraints of auto-increment).
	column2 = name (with data-type:varchar & length:50).
	column3 = file (with datatype:LONGBLOB).
	column4 = created_at(with datatype:date).

4)Now run the upload_image_in_pdf.php file

5)Browse the image to upload in database and click submit to upload image.

6)After upload Message will display 'Sucessful uploaded'.

7)You can view the stored image on button click of 'Display Data' 

8)To view the all stored images in pdf click on 'view PDF'. 
		 
9)Thank You