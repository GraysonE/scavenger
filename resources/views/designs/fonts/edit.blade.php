<div class="panel-body">
  <div class="form-group">
    <label for="global_nav_text">Global Navigation Text</label>
    <div class="input-group input-group-select">
      <select name="global_navigation_font" class="form-control">
          
          <?php
          if ($design->global_navigation_font == 'Lato') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Lato" {{ $selected }}>Lato</option>
          <?php
          if ($design->global_navigation_font == 'Cinzel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Cinzel" {{ $selected }}>Cinzel</option>
          <?php
          if ($design->global_navigation_font == 'Abel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Abel" {{ $selected }}>Abel</option>
          <?php
          if ($design->global_navigation_font == 'Open+Sans+Condensed:300') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Open+Sans+Condensed:300" {{ $selected }}>Open Sans Condensed</option>
          <?php
          if ($design->global_navigation_font == 'Fjord+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjord+One" {{ $selected }}>Fjord One</option>
          <?php
          if ($design->global_navigation_font == 'Oswald') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Oswald" {{ $selected }}>Oswald</option>
          <?php
          if ($design->global_navigation_font == 'Montserrat') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Montserrat" {{ $selected }}>Montserrat</option>
          <?php
          if ($design->global_navigation_font == 'Merriweather') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Merriweather" {{ $selected }}>Merriweather</option>
          <?php
          if ($design->global_navigation_font == 'Fjalla+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjalla+One" {{ $selected }}>Fjalla One</option>
          <?php
          if ($design->global_navigation_font == 'Playfair+Display') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Playfair+Display" {{ $selected }}>Playfair Display</option>
          
      </select>
    </div>
    <span class="preview header">LIVE PREVIEW HERE</span>
  </div>
  <div class="form-group">
    <label for="section_header_text">Section Header Text</label>
    <div class="input-group input-group-select">
      <select name="header_font" class="form-control">
        <?php
          if ($design->header_font == 'Lato') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Lato" {{ $selected }}>Lato</option>
          <?php
          if ($design->header_font == 'Cinzel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Cinzel" {{ $selected }}>Cinzel</option>
          <?php
          if ($design->header_font == 'Abel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Abel" {{ $selected }}>Abel</option>
          <?php
          if ($design->header_font == 'Open+Sans+Condensed:300') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Open+Sans+Condensed:300" {{ $selected }}>Open Sans Condensed</option>
          <?php
          if ($design->header_font == 'Fjord+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjord+One" {{ $selected }}>Fjord One</option>
          <?php
          if ($design->header_font == 'Oswald') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Oswald" {{ $selected }}>Oswald</option>
          <?php
          if ($design->header_font == 'Montserrat') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Montserrat" {{ $selected }}>Montserrat</option>
          <?php
          if ($design->header_font == 'Merriweather') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Merriweather" {{ $selected }}>Merriweather</option>
          <?php
          if ($design->header_font == 'Fjalla+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjalla+One" {{ $selected }}>Fjalla One</option>
          <?php
          if ($design->header_font == 'Playfair+Display') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Playfair+Display" {{ $selected }}>Playfair Display</option>
      </select>
    </div>
    <span class="preview header">LIVE PREVIEW HERE</span>
  </div>
  <div class="form-group">
    <label for="paragraph_text">Paragraph Text</label>
    <div class="input-group input-group-select">
      <select name="paragraph_font" class="form-control">
        <?php
          if ($design->paragraph_font == 'Lato') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Lato" {{ $selected }}>Lato</option>
          <?php
          if ($design->paragraph_font == 'Cinzel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Cinzel" {{ $selected }}>Cinzel</option>
          <?php
          if ($design->paragraph_font == 'Abel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Abel" {{ $selected }}>Abel</option>
          <?php
          if ($design->paragraph_font == 'Open+Sans+Condensed:300') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Open+Sans+Condensed:300" {{ $selected }}>Open Sans Condensed</option>
          <?php
          if ($design->paragraph_font == 'Fjord+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjord+One" {{ $selected }}>Fjord One</option>
          <?php
          if ($design->paragraph_font == 'Oswald') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Oswald" {{ $selected }}>Oswald</option>
          <?php
          if ($design->paragraph_font == 'Montserrat') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Montserrat" {{ $selected }}>Montserrat</option>
          <?php
          if ($design->paragraph_font == 'Merriweather') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Merriweather" {{ $selected }}>Merriweather</option>
          <?php
          if ($design->paragraph_font == 'Fjalla+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjalla+One" {{ $selected }}>Fjalla One</option>
          <?php
          if ($design->paragraph_font == 'Playfair+Display') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Playfair+Display" {{ $selected }}>Playfair Display</option>
      </select>
    </div>
    <span class="preview">Live Preview Here</span>
  </div>
  <div class="form-group">
    <label for="footer_text">Footer Text</label>
    <div class="input-group input-group-select">
      <select name="footer_font" class="form-control">
        <?php
          if ($design->footer_font == 'Lato') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Lato" {{ $selected }}>Lato</option>
          <?php
          if ($design->footer_font == 'Cinzel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Cinzel" {{ $selected }}>Cinzel</option>
          <?php
          if ($design->footer_font == 'Abel') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Abel" {{ $selected }}>Abel</option>
          <?php
          if ($design->footer_font == 'Open+Sans+Condensed:300') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Open+Sans+Condensed:300" {{ $selected }}>Open Sans Condensed</option>
          <?php
          if ($design->footer_font == 'Fjord+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjord+One" {{ $selected }}>Fjord One</option>
          <?php
          if ($design->footer_font == 'Oswald') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Oswald" {{ $selected }}>Oswald</option>
          <?php
          if ($design->footer_font == 'Montserrat') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Montserrat" {{ $selected }}>Montserrat</option>
          <?php
          if ($design->footer_font == 'Merriweather') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Merriweather" {{ $selected }}>Merriweather</option>
          <?php
          if ($design->footer_font == 'Fjalla+One') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Fjalla+One" {{ $selected }}>Fjalla One</option>
          <?php
          if ($design->footer_font == 'Playfair+Display') {
            $selected = 'selected';
          } else {
            $selected = '';
          }
          ?>
          <option value="Playfair+Display" {{ $selected }}>Playfair Display</option>
      </select>
    </div>
    <span class="preview">Live Preview Here</span>
  </div>
</div>