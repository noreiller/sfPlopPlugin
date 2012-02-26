<?php echo isset($css) ? '@import url("' . $css . '");' : null; ?>

body[class] [class] {
<?php if (isset($bgClr) && (trim($bgClr) != '')): ?>
  background-color: <?php echo $bgClr ?>;
<?php endif; ?>
<?php if (isset($bgImg) && (trim($bgImg) != '')): ?>
  background-image: url(<?php echo $bgImg ?>);
  background-repeat: <?php echo $bgRpt ?>;
  background-position: <?php echo $bgPsH ?> <?php echo $bgPsV ?>;
<?php endif; ?>
}
body[class]  #container .w-block {
<?php if (isset($wrpBgClr) && (trim($wrpBgClr) != '')): ?>
  background-color: <?php echo $wrpBgClr ?>;
<?php endif; ?>
<?php if (isset($wrpBgImg) && (trim($wrpBgImg) != '')): ?>
  background-image: url(<?php echo $wrpBgImg ?>);
  background-repeat: <?php echo $wrpBgRpt ?>;
  background-position: <?php echo $wrpBgPsH ?> <?php echo $wrpBgPsV ?>;
<?php endif; ?>
<?php if (($blckBrdrSz != '0') && isset($wrpBrdrClr) && (trim($wrpBrdrClr) != '')): ?>
  border-color: <?php echo $wrpBrdrClr ?>;
  border-width: <?php echo $wrpBrdrSz ?>;
  border-style: <?php echo $wrpBrdrStl ?>;
<?php endif; ?>
  width: <?php echo sfPlop::get('sf_plop_website_width') ?>;
}
body[class]  #container .w-block {
<?php if (isset($blckBgClr) && (trim($blckBgClr) != '')): ?>
  background-color: <?php echo $blckBgClr ?>;
<?php endif; ?>
<?php if (isset($blckBgImg) && (trim($blckBgImg) != '')): ?>
  background-image: url(<?php echo $blckBgImg ?>);
  background-repeat: <?php echo $blckBgRpt ?>;
  background-position: <?php echo $blckBgPsH ?> <?php echo $blckBgPsV ?>;
<?php endif; ?>
<?php if (($blckBrdrSz != '0') && isset($blckBrdrClr) && (trim($blckBrdrClr) != '')): ?>
  border-color: <?php echo $blckBrdrClr ?>;
  border-width: <?php echo $blckBrdrSz ?>;
  border-style: <?php echo $blckBrdrStl ?>;
<?php endif; ?>
<?php if (isset($blckTxtClr) && (trim($blckTxtClr) != '')): ?>
  color: <?php echo $blckTxtClr ?>;
<?php endif; ?>
<?php if (isset($blckTxtFnt) && (trim($blckTxtFnt) != '')): ?>
  font-family: <?php echo $blckTxtFnt ?>;
<?php endif; ?>
<?php if (isset($blckTxtSz) && (trim($blckTxtSz) != '')): ?>
  font-size: <?php echo $blckTxtSz ?>;
  line-height: <?php echo $blckTxtSz ?>;
<?php endif; ?>
}
body[class]  #container .w-block a {
<?php if (isset($blckLnkClr) && (trim($blckLnkClr) != '')): ?>
  color: <?php echo $blckLnkClr ?>;
<?php endif; ?>
}
body[class]  #container .w-block strong, #container .w-block b {
<?php if (isset($blckBldClr) && (trim($blckBldClr) != '')): ?>
  color: <?php echo $blckBldClr ?>;
<?php endif; ?>
}
body[class]  #container .w-block em, #container .w-block i i {
<?php if (isset($blckItlcClr) && (trim($blckItlcClr) != '')): ?>
  color: <?php echo $blckItlcClr ?>;
<?php endif; ?>
}

body[class]  #container .l .w-block {
<?php if (isset($layoutL) && (trim($layoutL) != '')): ?>
  width: <?php echo $layoutL ?>;
<?php endif; ?>
}

body[class]  #container .c .w-block {
<?php if (isset($layoutC) && (trim($layoutC) != '')): ?>
  width: <?php echo $layoutC ?>;
<?php endif; ?>
}

body[class]  #container .r .w-block {
<?php if (isset($layoutR) && (trim($layoutR) != '')): ?>
  width: <?php echo $layoutR ?>;
<?php endif; ?>
}

body[class]  #container .lc .w-block {
<?php if (isset($layoutLC) && (trim($layoutLC) != '')): ?>
  width: <?php echo $layoutLC ?>;
<?php endif; ?>
}

body[class]  #container .cr .w-block {
<?php if (isset($layoutCR) && (trim($layoutCR) != '')): ?>
  width: <?php echo $layoutCR ?>;
<?php endif; ?>
}