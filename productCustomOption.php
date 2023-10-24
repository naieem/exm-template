<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<style>
    input.btnPro {border: 1px solid transparent;padding: 6px 12px;border-radius: 4px;color: #333;background-color: #fff;border-color: #ccc;}
    p.inLabel{ text-transform: uppercase;font-weight: 600;}
    .products-dropdown input#yes {margin: 0px 0px 5px 0px;transform: scale(1.5);}
    .products-dropdown input#no {margin: 0px 0px 5px 0px;transform: scale(1.5);}
    label.form-check-label {margin-left: 3px;}
    .container.productOptions {border: 1px solid lightgrey;margin: 0px;width: 100%;border-bottom: none;}
    .col-md-6.englishVersion {border-right: 1px solid lightgray;}
    p#varsionLabel {text-transform: uppercase;font-weight: bold;text-decoration: underline;}
    p#frvarsionLabel {text-transform: uppercase;font-weight: bold;text-decoration: underline;}
    .result .product_info h3 {font-size: 15px;font-weight: bold;}
    .frResult .product_info h3 {font-size: 15px;font-weight: bold;}
    .result input[type="text"] {display: none;}
    .frResult input[type="text"] {display: none;}
    div#frProductTitle {font-size: 20px;font-weight: bolder;margin-bottom: 11px;text-decoration: underline;}
    .formforextra {background: whitesmoke;}
    .formforextra p.inLabel {margin-left: 7px;padding-top: 15px;}
    .formforextra .form-check {margin-left: 15px;}
    .formforextra input[type="text"] {margin-left: 15px;}
    input.btnPro {margin-left: 10px;margin-bottom: 15px;}
</style>
<?php
$success = "";
$keyIncludedErr = "";
$keyNumberErr = "";


if (isset($_POST['submit'])) 
{
    if (empty($_POST["key_included"]) || empty($_POST["key_number"])) 
    {  
        $keyIncludedErr = "* Key Included is required";
        $keyNumberErr   = "* Key Number is required";
    }
    else
    {
        $success   = '<div class="alert alert-success" role="alert"> Record synced Successfully! </div>';
    } 
    $productID     =  $_POST["product"];
    $frProductID   =  $_POST["frProductID"];
    $keyIncluded   =  $_POST["key_included"];
    $keyNumber     =  $_POST["key_number"];
    $product       =  wc_get_product( $productID );   
    $frProduct     =  wc_get_product( $frProductID );   
    
    if ($product->is_type( 'variable' ))
    {
        $variations  =  $product->get_available_variations();

        foreach($variations as $value)
        {

            $variationID      = $value['variation_id'];
            $variation        = wc_get_product($variationID);
            $variationDetails = $variation->get_formatted_name(); 
            
            update_post_meta( $variationID,'key_included',$keyIncluded);
            update_post_meta( $variationID,'key_number',$keyNumber);
            
        }
        
        $frvariations  =  $frProduct->get_available_variations();

        foreach($frvariations as $frvalue)
        {

            $frvariationID      = $frvalue['variation_id'];
            $frvariation        = wc_get_product($frvariationID);
            $frvariationDetails = $frvariation->get_formatted_name(); 
            
            update_post_meta( $frvariationID,'key_included',$keyIncluded);
            update_post_meta( $frvariationID,'key_number',$keyNumber);
            
        }
    }
    else
    {
        $productTypeErr = '<div class="notice notice-error " role="alert"> Please select Variable Product </div>';
    }
}

?>
<div class="container productOptions">
    <div class="row">
        <div class="col-md-6 englishVersion">
            <form action="" method="post">    
            <?php
            $query1 = new WP_Query(
                array(
                    'post_type'      => 'product',
                    'post_status'    => 'publish',
                    'posts_per_page' => '-1',
                    'lang'           => 'fr',
                    'tax_query'      => array(
                        array(
                            'taxonomy'      => 'product_cat',
                            'terms'         =>  'accessories',
                            'field'         => 'slug'
                        )
                    ),
                )
            );
            if ($query1->have_posts()) : ?>
                <div class="products-dropdown">
                    <h1>ENGLISH VERSION</h1>
                    <?php echo $success ?>
                    <br/>
                    <p class="inLabel">* Product List : </p>
                    <select name ="product" id="products-select" required>
                        <option value="">--- Choose a product ---</option>
                        <?php
                        while ($query1->have_posts()) : $query1->the_post();

                            echo '<option value="' . $query1->post->ID . '">' . $query1->post->post_title . '</option>';

                        endwhile;
                        ?>
                    </select>
                    <br/><br/>
                    <div class="result">
                    <p id="varsionLabel"></p>    
                    <p id="variationID"></p>    
                    <p id="variationsResult"></p>
                    <p id="variation_description"></p>
                    </div>    
                </div>    
                <?php 
            else:
                echo "not product found";
            endif;
            ?> 
        </div>  
        <div class="col-md-6 frenchVersion">      
            <div class="products-dropdown">
                <h1>FRENCH VERSION</h1>
                <div class="frResult">
                    <div id="frProductTitle"></div>
                    <p id="frvarsionLabel"></p>  
                    <div id="frproduct"></div>
                    <div id="frvariation_description"></div>
                </div>
                <div class="formforextra">
                    <p class="inLabel">* Key included : </p>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="yes" name="key_included"  value="yes">
                            <label class="form-check-label" for="yes">Oui</label><br>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="no" name="key_included" value="no">
                            <label class="form-check-label" for="no">No</label><br> 
                        </div>
                        <span class="error" style="color:red;"><?php echo $keyIncludedErr;?></span>
                        <br/>
                    <p class="inLabel">* Key Number : </p>
                        <input type="text" name="key_number" placeholder="e.g.K-KL1"><br/>
                        <span class="error" style="color:red;"><?php echo $keyNumberErr;?></span>
                        <br/>
                            <input type="hidden" name="frProductID" id="frProductID" value=''>
                            <input type="submit" name="submit" class="btnPro" value="Sync" >
                </div>               
            </div>
        </div>            
        </form>
    </div>
</div>

<script type="text/javascript">
  jQuery(document).ready(function(){ 
    jQuery("#products-select").change(function()
    { 
      var productID = jQuery(this).val(); 
        
      if(productID){
            jQuery.ajax ({
                type: 'POST',
                url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                data: 
                {
                    'action'    : 'productOptions_ajax_request', 
                    'productID' : productID 
                },
                success : function(resp) 
                {
                    response = JSON.parse(resp);
                    if (response.success == "true") 
                    {
                        // console.log(response);   
                        jQuery('#varsionLabel').html(response.type);
                        jQuery('#variationsResult').html(response.data_en);
                        jQuery('#frProductTitle').html(response.frtitle);
                        jQuery('#frProductID').val(response.frProductID);
                        jQuery('#frvarsionLabel').html(response.type);
                        jQuery('#frproduct').html(response.data_fr);
                        jQuery('#variation_description').html(response.variation_description);
                        jQuery('#frvariation_description').html(response.frvariation_description);
                    }
                }
            });
        }

    });
  });
</script>