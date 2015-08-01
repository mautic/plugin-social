<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic Contributors. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.org
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

$locale    = $app->getRequest()->getLocale();
$layout    = (!empty($settings['layout'])) ? $settings['layout'] : 'standard';
$action    = (!empty($settings['action'])) ? $settings['action'] : 'like';
$showFaces = (!empty($settings['showFaces'])) ? 'true' : 'false';
$showShare = (!empty($settings['showShare'])) ? 'true' : 'false';

$js = <<<JS
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/{$locale}/sdk.js#xfbml=1&appId={$settings['keys']['clientId']}&version=v2.0";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
JS;
?>

<?php //add FB's required OG tag ?>
<meta property="og:type" content="website" />
<div class="fb-<?php echo ($action == 'share') ? 'share-button' : 'like'; ?> share-button facebook-share-button layout-<?php echo $settings['layout']; ?> action-<?php echo $action; ?>"
     data-<?php echo ($action == 'share') ? 'type' : 'layout'; ?>="<?php echo $layout; ?>"
     <?php if ($action != 'share'): ?>
     data-action="<?php echo $action; ?>"
     data-show-faces="<?php echo $showFaces; ?>"
     data-share="<?php echo $showShare; ?>"
     <?php endif; ?>>
</div>
<?php echo $js; ?>
