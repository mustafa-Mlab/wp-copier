<?php 
$url = isset($_SESSION['url'])? $_SESSION['url'] : '';
$flag = false;
$list = [];
if(isset($_POST['list'])){
  if( isset($_POST['url']) && ! empty($_POST['url']) ){
    $_SESSION['url'] = rtrim($_POST['url'], "/");
    $url = $_SESSION['url'];
    if(!isset($_SESSION['list']) || $_SESSION['url'] != $url )
    $_SESSION['list'] = json_decode(file_get_contents($url . '/wp-json/custom/v1/all-posts-types'));
    $list = $_SESSION['list'];
    $flag = 1;
  }else{
    $flag = 0;
  } 
}

$checkboxArray = [];
$taxArray = [];
  // if( isset($_POST['submit']) && isset($_POST['posts'])){

  //   $postIDArray = $_POST['posts'];
  //   foreach($postIDArray as $postArrayKey => $postArrayValue){
  //     $posts = json_decode(file_get_contents($url . "/wp-json/custom/v1/my-posts?key={$postArrayValue}" ));
  //     if(! post_exists($posts[0]->post->post_title, '', '', $posts[0]->post_type)){
  //       // Create the post first
  //       $data = $posts[0];
  //       $thisID = $data->post->ID;
  //       foreach($data->post as $itemKey => $itemValue){
  //         $postArray[$itemKey] = $itemValue; 
  //       }
  //       $previousID = $postArray['ID'];
  //       $previousURL = $postArray['guid'];

  //       unset($postArray['ID']); // Wordpress will assign a new Id
  //       unset($postArray['guid']); // Wordpress will add new guid

  //       /** upload respactive image inside post content */
  //       $postContent = $postArray['post_content'];
  //       preg_match_all('/<img[^>]+>/i',$postContent, $result); 
  //       $img = array();
  //       foreach( $result as $implementedResult){
  //         foreach( $implementedResult as $img_tag){
  //           preg_match_all('/(src=")([^"]*)"/i',$img_tag, $img[$img_tag]);
  //         }
  //       }
        
  //       $processedImg = array();
  //       foreach($img as $imgKey => $imgTag){
  //         $attachmentDetails = save_attachement( 
  //           array( 'url' => preg_replace('/(-[0-9]*x[0-9]*)/m', '', end($imgTag)[0]), 'mainServerLocation' => $data->upload_dir ) 
  //         );
  //         if($attachmentDetails['id']){
  //           $processedImg[end($imgTag)[0]] = $attachmentDetails['id'];
  //         }else{
  //           $processedImg[end($imgTag)[0]] = $attachmentDetails['url'];
  //         }
  //       }
  //       foreach($processedImg as $mainLink => $actualLink){
  //         $postContent = str_replace( $mainLink,$actualLink, $postContent);
  //       }
  //       $postArray['post_content'] = $postContent;
  //       /** upload respactive image inside post content */
        
  //       $postArray['post_author'] = get_current_user_id(); // Current user will be the author of this post
  //       $newID = wp_insert_post($postArray); // Inserting new post 

  //       $terms = $data->terms;
        
  //       foreach ($terms as $objectArrayKey => $objectArrayValue){
  //         foreach($objectArrayValue as $key => $value){
  //           if(term_exists( $value->slug, $value->taxonomy )){
  //             $thisTerm = term_exists( $value->slug, $value->taxonomy );
  //           }else{
  //             $thisTerm = wp_insert_term($value->name, $value->taxonomy, array('description' => $value->description, 'parent' => $value->parent, 'slug' => $value->slug  ) );
  //           }
  //           wp_set_object_terms( $newID, (int)$thisTerm['term_id'], $value->taxonomy );
  //         }
  //       }
        
  //       // Now we have new post Id in $newID lets add all metas
  //       add_post_meta( $newID, 'wp-copier', '1');
  //       add_post_meta( $newID, 'previous-id', $previousID);
  //       add_post_meta( $newID, 'previous-url', $previousURL);
  //       add_post_meta( $newID, 'copy-from', $url);

  //       $postMeta = $data->post_meta;
  //       foreach($postMeta as $postMetaKey => $postMetaValue){

  //         // Lets replace the thumbnil or feature image first: 
  //         if($postMetaKey === '_thumbnail_id'){ 
  //           // get the thumbnil image src / url 
            
  //           $attachmentDetails = save_attachement( 
  //             array( 'url' => $data->featured_img_src, 'id' => $postMetaValue[0], 'mainServerLocation' => $data->upload_dir ) 
  //           );
  //           add_post_meta($newID, $postMetaKey, $attachmentDetails['id']);
  //         } 

  //         // Now change the codestars meta boxs image links
  //         else{
  //           // By deafult all the meta are repreasnting by array
  //           if(is_array($postMetaValue)){
              
  //             foreach($postMetaValue as $innerArrayMetaValueKey => $innerArrayMetaValue ){
  //               /** wordprress deafult metas will be theonly entry inside the array
  //                 * but codestars meta could have multiple entry
  //                 * in this loop all the meta will extract one by one inside the array, and will check if the value is serialized
  //                 * If value is serialized then it is the value from codestrs framework
  //                 * if we get any serialized value we will unseralize theme and loop each content for image or media url
  //                 * if we find any we will replace the link and ID then serialize all data again and push to post meta
  //               */
  //               if(is_serialized( $innerArrayMetaValue )){
  //                 $thisInnerArrayMetaValue = unserialize($innerArrayMetaValue);
  //                 foreach($thisInnerArrayMetaValue as $metaValuename => $metaValueData){
  //                   $thisInnerArrayMetaValue[$metaValuename] = processMetadatas($metaValueData, $data->upload_dir, $url);
  //                 }
  //                 add_post_meta($newID, $postMetaKey, $thisInnerArrayMetaValue);
  //               }else{
  //                 // If not serialized data
  //                 add_post_meta($newID, $postMetaKey, $innerArrayMetaValue);
  //               }
  //             }
  //           }
  //         }
  //       } 
  //     }
  //   }
  // }
  if( ! $flag ){
    unset($_SESSION['url']);
    unset($_SESSION['list']);
  }
?>

<div class="wrap">
  <h1 class="wp-heading-inline">WP Copier Settings</h1>
  <br><br>
  <hr class="wp-header-end">

  <div class="form">
  
  <?php if(!$flag){
    echo '<div class="form-error">';
    echo "Please select at least one post type and input main site url ";
    echo '</div> <br><br>';
  }  ?>

  <form action="#" name="list_form" method="post">
  <label for="url">Main Site URL</label>
  <input type="text" name="url" id="url" value="<?= $url;?>" required>
  <br>
  <br>
  <input type="submit" value="Get Post Types List" name="list" >
  </form>
  <br>
  <br>
  <?php if( isset($_SESSION['list'])){  ?>

    <form action="#" name="wp_copier_form" id="wp_copier_form" method="post">
    <h2>Post Types</h2>
    <?php 
      $postTypes = $_SESSION['list']->post_types;
      unset($postTypes->revision);
      unset($postTypes->nav_menu_item);
      unset($postTypes->custom_css);
      unset($postTypes->customize_changeset);
      unset($postTypes->oembed_cache);
      unset($postTypes->user_request);
      unset($postTypes->attachment);
      foreach($postTypes as $key => $value){
    ?>
      <div class="postTyperow">
        <label> 
          <input type="checkbox" name="checkboxArray[]" class="postTypeInputBox" value="<?= $value; ?>" <?= ( $flag && in_array($value , $checkboxArray)) ? 'checked' :''; ?>>
          <?= ucfirst($value); ?>
        </label><br>
        <div class="fetch-data">
          <div class="all-section">
            <label>
              <input type="checkbox" name="get-all" id="get-all-<?= $value;?>" class="get-all" checked>
              Scrab All <span class="post-counter"></span>
            </label>
          </div>
          <div class="id-specific-section">
            <ul class="id-specific-posts">
            </ul>
          </div>
        </div>
      </div>
      <?php  } ?>
    
    <br> <br>
    <input type="submit" value="Start Copy" name="submit" style="position: fixed; bottom: 50px; right: 2%;">
  </form>

  <div class="report">
    <button class="close-repost">X</button>
    <h4 class="status"></h4>
    <div class="loading"><img src="<?= plugin_dir_url( dirname( __FILE__ ) ) ?>assets/loading.gif" alt="loading"></div>
    <div class="item-list">
      <ul class="items-started"></ul>
      <ul class="items-finished"></ul>  
    </div>
    <h4 class="status-ends"></h4>
  </div>
  <?php }  ?>
  </div>
</div>

<?php 

