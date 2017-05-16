
(function ($) {

Drupal.behaviors.commentFieldsetSummaries = {
  attach: function (context) {
    var $fieldset = $('fieldset.og-sm-comment-node-settings-form', context);

    // Set the summary based on the select value.
    $fieldset.drupalSetSummary(function (context) {
      var comment_default = $('input[name="comment_default"]', $fieldset).val();
      var comment_override = $('input[@id="edit-comment-override"]:checked', $fieldset).length;
      var comment_override_value = $('#edit-comment-override-value option:selected', $fieldset).text();

      if (!comment_override) {
        return Drupal.checkPlain(comment_default);
      }

      return Drupal.checkPlain(comment_override_value) + ' (' + Drupal.t('overridden') + ')';
    });
  }
};

})(jQuery);
