 cleanupstyles Plugin for tinyMCE
---------------------------------
Author: David Aurelio <d DOT aurelio AT gmx DOT net>
Version: 0.1.1
Changelog:
     * Mon Jun 27 2005 0.1.1
     - fixed "everything allowed" when no valid property passed
     * Mon Jun 27 2005 0.1
     - initial release, hopefully also final realease

About:
     This plugin enhances tinyMCE by the capability to control inline styles.
     You can specify which css properties may appear in inline-styles, all other
     properties will be removed on cleanup.

Installation instructions:
  * Copy the cleanupstyles directory to the plugins directory of TinyMCE (/jscripts/tiny_mce/plugins).
  * Add plugin to TinyMCE plugin option list example: plugins : "cleanupstyles".
  * pass a comma-seperated lists of valid css properties by using the "valid_css_properties" option.
    Note that 'property' also allows 'property-subproperty' etc. Contrariwise 'property-subproperty' doesn't allow 'property'.
    Example: 'padding' also allows 'padding-left'. 'padding-bottom' doesn't allow 'padding'.

Initialization example:
  tinyMCE.init({
    theme : "advanced",
    mode : "textareas",
    plugins : "cleanupstyles",
    valid_css_properties : "margin-left, color, padding, line-height"

  });
