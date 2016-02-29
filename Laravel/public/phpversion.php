<!DOCTYPE html> 
 <html> 
   <head> 
     
   </head> 
   <body> 
        <header> 
          <h1>My Site</h1>
          <meta http-equiv="Content-Type" content="text/html; charset=utf8"> 
        </header> 
         <nav> 
         </nav> 
       <section> 
           <h1>My Article</h1> 
            <article> 
               <form action="" method="post" enctype="multipart/form-data" id="uploadImage">
         <p>
         <label for="image">Upload image:</label>
         <input type="hidden" name="MAX_FILE_SIZE" value="<?= $max; ?>">
         <input type="file" name="image" id="image">
         </p>
         <p>
          <input type="submit" name="upload" id="upload" value="Upload">
         </p> 
              </form>
 
          </article> 
       </section>
        <pre>
          <?php
          use PhpSolutions\File\Upload;
          if (isset($_POST['upload'])) {
            // define the path to the upload folder
           $destination = '/var/www/mail/26/simple/Laravel/public/uploads/';
            // move the file to the upload folder and rename it 
           require_once 'PhpSolutions/File/Upload.php';

           try {
               $loader = new Upload($destination);
               $loader->upload();
               $result = $loader->getErrorMessage();
               } catch (Exception $e) {
                      echo $e->getErrorMessage();
                 }
            }
         ?> </pre> 
      <footer>
            <?php
            if (isset($result)) {
                echo '<ul>';
                foreach ($result as $message) {
                echo "<li>$message</li>";
             }
                 echo '</ul>';
             }
             ?> 
           <p>...</p> 
        </footer> 
     </body> 
</html>  
