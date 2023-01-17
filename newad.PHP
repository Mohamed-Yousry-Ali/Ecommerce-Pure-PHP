<?php
    session_start();
    $pagetitle ='Create New Item';
    include 'init.php';
    if(isset($_SESSION['user'])){
        if($_SERVER['REQUEST_METHOD']=='POST'){
            $formErrors = array();
            $name      = filter_var($_POST['name'] , FILTER_SANITIZE_STRING);
            $desc      = filter_var($_POST['description'] , FILTER_SANITIZE_STRING);
            $price     = filter_var($_POST['price'] , FILTER_SANITIZE_NUMBER_INT);
            $country   = filter_var($_POST['country'] , FILTER_SANITIZE_STRING);
            $status    = filter_var($_POST['status'] , FILTER_SANITIZE_NUMBER_INT);
            $category  = filter_var($_POST['categorey'] , FILTER_SANITIZE_NUMBER_INT);
            $tags      = filter_var($_POST['tags'] , FILTER_SANITIZE_STRING);
            if(strlen($name) < 3){
                $formErrors [] = 'Title Must Be More Then 3 Charcters';
            }

            //Check if no Error Make Update
            if( empty($formErrors)){
            
        
            //Insert Info to database 
                $stmt =$con->prepare("INSERT INTO items(Name , Description , Price , Country_Made , Status , Add_Date , Cat_ID , Member_ID , tags)
                                    VALUES (:zname , :zdesc , :zprice , :zcountry , :zstatus , now() , :zcat , :zmember , :ztags) ");
                $stmt->execute(array(
                    'zname'    => $name,
                    'zdesc'    => $desc,
                    'zprice'   => $price,
                    'zcountry' => $country,
                    'zstatus'  => $status,
                    'zcat'     => $category,
                    'zmember'  => $_SESSION['uid'],
                    'ztags'    => $tags
                ));                    
             if($stmt){
                $successMsg= 'Added Success';
             }

                    
            }
        }

?>
    <h2 class="text-center"><?php echo $pagetitle?></h2>
    <div class="create-ad block">
        <div class="container">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo $pagetitle?></div>
                <div class="panel-body">
                   <div class="row">
                    <div class="col-md-8">
                    <form class="form-horizontal main-form" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
                        <div class="form-group form-group-lg">
                            <label  class="col-sm-3 control-label">Name</label>
                            <div class="col-sm-10 col-md-9">
                                <input
                                type="text"
                                name="name"
                                class="form-control live"
                                required
                                placeholder="Name Of Item"
                                data-class =".live-title">
                            </div>
                        </div>

                        <div class="form-group form-group-lg">
                                    <label  class="col-sm-3 control-label">Description</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                        type="text"
                                        name="description"
                                        class="form-control live"
                                        required
                                        placeholder="Add Description"
                                        data-class =".live-desc">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label  class="col-sm-3 control-label">Price</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                            type="text"
                                            name="price"
                                            class="form-control live"
                                            required
                                            placeholder="Add Price"
                                            data-class =".live-price">
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label  class="col-sm-3 control-label">Country</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                            type="text"
                                            name="country"
                                            class="form-control"
                                            required
                                            placeholder="Country Of Made">
                                    </div>
                                </div>


                                <div class="form-group form-group-lg">
                                    <label  class="col-sm-3 control-label">Status</label>
                                    <div class="col-sm-10 col-md-9">
                                    <select  name="status" required>
                                        <option value="">...</option>
                                        <option value="1">New</option>
                                        <option value="2">Hight Copy</option>
                                        <option value="3">Used</option>
                                        <option value="4">Old</option>
                                    </select>
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label  class="col-sm-3 control-label">categorey</label>
                                    <div class="col-sm-10 col-md-9">
                                    <select  name="categorey" required>
                                        <option value="">...</option>
                                        <?php
                                           //$cats = getAllFrom2('categories','ID');
                                          $cats = getAllFrom("*", "categories" , "WHERE parent = 0" ,"" ,"ID" , "ASC");
                                            foreach($cats as $cat){
                                                echo "<option value='".$cat['ID']."'>".$cat['Name']."</option>";
                                            }
                                        ?>
                                    </select>
                                    </div>
                                </div>

                                <div class="form-group form-group-lg">
                                    <label  class="col-sm-3 control-label">Tags</label>
                                    <div class="col-sm-10 col-md-9">
                                        <input
                                            type="text"
                                            name="tags"
                                            class="form-control"
                                            placeholder="Tag For Descripe Your Item">
                                </div>
                            </div>

                            <div class="form-group form-group-lg">
                                
                                <div class="col-sm-offset-3 col-sm-9">
                                    <input type="submit" value="Add Item" class="btn btn-primary btn-lg">
                                </div>
                            </div>
                            </form>
                    </div>
                          <div class="col-md-4">
                     <div class="thumbnail item-box live-preview">
                     <span class="price-tag">
                        $<span class="live-price"></span>
                    </span>
                     <img class="img-responsive" src="Sample_User_Icon.png" alt=""/>
                     <div class="caption">
                     <h3 class="live-title">Title</h3>
                     <p class="live-desc">Description</p>
                     </div>
                     </div>
                    </div>
                   </div>
                   <?php 
                   if(!empty($formErrors)){
                    foreach($formErrors as $error){
                        echo '<div class="alert alert-danger">' . $error . '</div>';
                    }
                   }
                   if(isset($successMsg)){
                    echo '<div class="alert alert-success">' . $successMsg . '</div>';
                   }
                   ?>
                </div>
            </div>
        </div>
    </div>

   
<?php
}else{
    header('Location: login.php');
    exit();
}
    include $tpl.'footer.php';
 ?>