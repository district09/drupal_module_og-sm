<?php
/**
 * @file
 * API documentation about the og_sm module.
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Act when a node type is added to the list of Site node type.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param string $type
 *   The node type machine name.
 *
 * @see og_sm_site_type_add()
 */
function hook_og_sm_site_type_add($type) {

}

/**
 * Act when a node type is removed from the list of Site node types.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param string $type
 *   The node type machine name.
 *
 * @see og_sm_site_type_remove()
 */
function hook_og_sm_site_type_remove($type) {

}

/**
 * Act on a Site node being viewed.
 *
 * Will only be triggered when the node_view hook is triggered for a node type
 * that is a Site type.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param object $site
 *   The Site node object.
 * @param string $view_mode
 *   The view mode for the Site node.
 * @param string $langcode
 *   The language code.
 *
 * @see hook_node_view()
 */
function hook_og_sm_site_view($site, $view_mode, $langcode) {

}

/**
 * Act on a Site node being inserted or updated.
 *
 * Will only be triggered when the node_presave hook is triggered for a node
 * type that is a Site type.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param object $site
 *   The Site node object.
 *
 * @see hook_node_presave()
 */
function hook_og_sm_site_presave($site) {

}

/**
 * Act on a Site node about to be shown on the add/edit form.
 *
 * Will only be triggered when the node_prepare hook is triggered for a node
 * type that is a Site type.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param object $site
 *   The Site node object.
 *
 * @see hook_node_prepare()
 */
function hook_og_sm_site_prepare($site) {

}

/**
 * Act on a Site node being inserted.
 *
 * Will only be triggered when the node_insert hook is triggered for a node type
 * that is a Site type.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param object $site
 *   The Site node object.
 *
 * @see hook_node_insert()
 */
function hook_og_sm_site_insert($site) {

}

/**
 * Act on a Site node being updated.
 *
 * Will only be triggered when the node_update hook is triggered for a node type
 * that is a Site type.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param object $site
 *   The Site node object.
 *
 * @see hook_node_update()
 */
function hook_og_sm_site_update($site) {

}

/**
 * Act on a Site node being deleted.
 *
 * Will only be triggered when the node_delete hook is triggered for a node type
 * that is a Site type.
 *
 * The hook can be put in the yourmodule.module OR in the yourmodule.og_sm.inc
 * file. The recommended place is in the yourmodule.og_sm.inc file as it keeps
 * your .module file cleaner and makes the platform load less code by default.
 *
 * @param object $site
 *   The Site node object.
 *
 * @see hook_node_delete()
 */
function hook_og_sm_site_delete($site) {

}

/**
 * @} End of "addtogroup hooks".
 */
