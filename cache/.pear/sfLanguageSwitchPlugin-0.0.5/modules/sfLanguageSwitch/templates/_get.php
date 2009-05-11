<ul id="lang">
  <?php foreach($sf_data->getRaw('languages') as $language => $information): ?>
    <li<?php echo $language == $sf_user->getCulture() ? ' class="active"' : '' ?>>
      <?php echo link_to(
        image_tag(
	  $information['image'], 
	  array(
	    'alt' => $information['title']
	  )
	),
        $current_module . '/' . $current_action . $information['query']
      ) ?>
    </li>
  <?php endforeach; ?>
</ul>
