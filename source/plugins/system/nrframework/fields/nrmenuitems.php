<?php
/**
 * @author          Tassos Marinos <info@tassos.gr>
 * @link            http://www.tassos.gr
 * @copyright       Copyright © 2016 Tassos Marinos All Rights Reserved
 * @license         GNU GPLv3 <http://www.gnu.org/licenses/gpl.html> or later
 */

defined('_JEXEC') or die;

require_once dirname(__DIR__) . '/helpers/field.php';
require_once dirname(__DIR__) . '/helpers/functions.php';

class JFormFieldNRMenuItems extends NRFormField
{
	/**
	 * Output the HTML for the field
	 * Example of usage: <field name="field_name" type="nrmenuitems" label="NR_SELECTION" />
	 */
	protected function getInput()
	{
		$size    = $this->get('size', 300);
		$options = $this->getMenuItems();

		return $this->treeselect($options, $this->name, $this->value, $this->id, $size);
	}

	/**
	 * Get a list of menu links for one or all menus.
	 * Logic from administrator\components\com_menus\helpers\menus.php@getMenuLinks()
	 */
	public function getMenuItems()
	{
		NRFrameworkFunctions::loadLanguage('com_menus', JPATH_ADMINISTRATOR);
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true)
			->select('a.id AS value, a.title AS text, a.alias, a.level, a.menutype, a.type, a.template_style_id, a.checked_out, a.language')
			->from('#__menu AS a')
			->join('LEFT', $db->quoteName('#__menu') . ' AS b ON a.lft > b.lft AND a.rgt < b.rgt')
			->where('a.published != -2')
			->group('a.id, a.title, a.level, a.menutype, a.type, a.template_style_id, a.checked_out, a.lft')
			->order('a.lft ASC');

		// Get the options.
		$db->setQuery($query);

		try
		{
			$links = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());

			return false;
		}

		// Group the items by menutype.
		$query->clear()
			->select('*')
			->from('#__menu_types')
			->where('menutype <> ' . $db->quote(''))
			->order('title, menutype');
		$db->setQuery($query);

		try
		{
			$menuTypes = $db->loadObjectList();
		}
		catch (RuntimeException $e)
		{
			JError::raiseWarning(500, $e->getMessage());

			return false;
		}

		// Create a reverse lookup and aggregate the links.
		$rlu = array();
		foreach ($menuTypes as &$type)
		{
			$type->value      = 'type.' . $type->menutype;
			$type->text       = $type->title;
			$type->level      = 0;
			$type->class      = 'hidechildren';
			$type->labelclass = 'nav-header';

			$rlu[$type->menutype] = &$type;
			$type->links          = array();
		}

		foreach ($links as &$link)
		{
			if (isset($rlu[$link->menutype]))
			{
				if (preg_replace('#[^a-z0-9]#', '', strtolower($link->text)) !== preg_replace('#[^a-z0-9]#', '', $link->alias))
				{
					$link->text .= ' <small>[' . $link->alias . ']</small>';
				}

				if ($link->language && $link->language != '*')
				{
					$link->text .= ' <small>(' . $link->language . ')</small>';
				}

				if ($link->type == 'alias')
				{
					$link->text .= ' <small>(' . JText::_('COM_MENUS_TYPE_ALIAS') . ')</small>';
					$link->disable = 1;
				}

				$rlu[$link->menutype]->links[] = &$link;

				unset($link->menutype);
			}
		}

		return $menuTypes;
	}

	/**
	 * Construct the HTML for the input field in a tree
	 * Logic from administrator\components\com_modules\views\module\tmpl\edit_assignment.php
	 */
	protected function treeselect(&$options, $name, $value, $id, $size = 300)
	{
		NRFrameworkFunctions::loadLanguage('com_menus', JPATH_ADMINISTRATOR);
		NRFrameworkFunctions::loadLanguage('com_modules', JPATH_ADMINISTRATOR);
		if (empty($options))
		{
			return '<fieldset class="radio">' . JText::_('NR_NO_ITEMS_FOUND') . '</fieldset>';
		}

		if (!is_array($value))
		{
			$value = explode(',', $value);
		}

		$count = 0;
		if ($options != -1)
		{
			foreach ($options as $option)
			{
				$count++;
				if (isset($option->links))
				{
					$count += count($option->links);
				}
			}
		}

		if ($options == -1)
		{
			if (is_array($value))
			{
				$value = implode(',', $value);
			}
			if (!$value)
			{
				$input = '<textarea name="' . $name . '" id="' . $id . '" cols="40" rows="5">' . $value . '</textarea>';
			}
			else
			{
				$input = '<input type="text" name="' . $name . '" id="' . $id . '" value="' . $value . '" size="60">';
			}

			return '<fieldset class="radio"><label for="' . $id . '">' . JText::_('NR_ITEM_IDS') . ':</label>' . $input . '</fieldset>';
		}

		$this->doc->addScript(JURI::root(true) . '/plugins/system/nrframework/fields/assets/treeselect.js');
		$this->doc->addStyleSheet(JURI::root(true) . '/plugins/system/nrframework/fields/assets/treeselect.css');

		$html = array();

		$html[] = '<div class="well well-small nr_treeselect" id="' . $id . '">';
		$html[] = '
			<div class="form-inline nr_treeselect-controls">
				<span class="small">' . JText::_('JSELECT') . ':
					<a class="nr_treeselect-checkall" href="javascript:;">' . JText::_('JALL') . '</a>,
					<a class="nr_treeselect-uncheckall" href="javascript:;">' . JText::_('JNONE') . '</a>,
					<a class="nr_treeselect-toggleall" href="javascript:;">' . JText::_('NR_TOGGLE') . '</a>
				</span>
				<span class="width-20">|</span>
				<span class="small">' . JText::_('NR_EXPAND') . ':
					<a class="nr_treeselect-expandall" href="javascript:;">' . JText::_('JALL') . '</a>,
					<a class="nr_treeselect-collapseall" href="javascript:;">' . JText::_('JNONE') . '</a>
				</span>
				<span class="width-20">|</span>
				<span class="small">' . JText::_('JSHOW') . ':
					<a class="nr_treeselect-showall" href="javascript:;">' . JText::_('JALL') . '</a>,
					<a class="nr_treeselect-showselected" href="javascript:;">' . JText::_('NR_SELECTED') . '</a>
				</span>
				<span class="nr_treeselect-maxmin">
				<span class="width-20">|</span>
				<span class="small">
					<a class="nr_treeselect-maximize" href="javascript:;">' . JText::_('NR_MAXIMIZE') . '</a>
					<a class="nr_treeselect-minimize" style="display:none;" href="javascript:;">' . JText::_('NR_MINIMIZE') . '</a>
				</span>
				</span>
				<input type="text" name="nr_treeselect-filter" class="nr_treeselect-filter input-medium search-query pull-right" size="16"
					autocomplete="off" placeholder="' . JText::_('JSEARCH_FILTER') . '" aria-invalid="false" tabindex="-1">
			</div>

			<div class="clearfix"></div>

			<hr class="hr-condensed">';

		$o = array();
		foreach ($options as $option)
		{
			$option->level = isset($option->level) ? $option->level : 0;
			$o[]           = $option;
			if (isset($option->links))
			{
				foreach ($option->links as $link)
				{
					$link->level = $option->level + (isset($link->level) ? $link->level : 1);
					$o[]         = $link;
				}
			}
		}

		$html[]    = '<ul class="nr_treeselect-ul" style="max-height:300px;min-width:' . $size . 'px;overflow-x: hidden;">';
		$prevlevel = 0;

		foreach ($o as $i => $option)
		{
			if ($prevlevel < $option->level)
			{
				// correct wrong level indentations
				$option->level = $prevlevel + 1;

				$html[] = '<ul class="nr_treeselect-sub">';
			}
			else if ($prevlevel > $option->level)
			{
				$html[] = str_repeat('</li></ul>', $prevlevel - $option->level);
			}
			else if ($i)
			{
				$html[] = '</li>';
			}

			$labelclass = trim('pull-left ' . (isset($option->labelclass) ? $option->labelclass : ''));

			$html[] = '<li>';

			$item = '<div class="' . trim('nr_treeselect-item pull-left ' . (isset($option->class) ? $option->class : '')) . '">';
			if (isset($option->title))
			{
				$labelclass .= ' nav-header';
			}

			if (isset($option->title) && (!isset($option->value) || !$option->value))
			{
				$item .= '<label class="' . $labelclass . '">' . $option->title . '</label>';
			}
			else
			{
				$selected = in_array($option->value, $value) ? ' checked="checked"' : '';
				$disabled = (isset($option->disable) && $option->disable) ? ' readonly="readonly" style="visibility:hidden"' : '';

				$item .= '<input type="checkbox" class="pull-left" name="' . $name . '" id="' . $id . $option->value . '" value="' . $option->value . '"' . $selected . $disabled . '>
					<label for="' . $id . $option->value . '" class="' . $labelclass . '">' . $option->text . '</label>';
			}
			$item .= '</div>';
			$html[] = $item;

			if (!isset($o[$i + 1]) && $option->level > 0)
			{
				$html[] = str_repeat('</li></ul>', (int) $option->level);
			}
			$prevlevel = $option->level;
		}
		$html[] = '</ul>';
		$html[] = '
			<div style="display:none;" class="nr_treeselect-menu-block">
				<div class="pull-left nav-hover nr_treeselect-menu">
					<div class="btn-group">
						<a href="#" data-toggle="dropdown" class="dropdown-toggle btn btn-micro">
							<span class="caret"></span>
						</a>
						<ul class="dropdown-menu">
							<li class="nav-header">' . JText::_('COM_MODULES_SUBITEMS') . '</li>
							<li class="divider"></li>
							<li class=""><a class="checkall" href="javascript:;"><span class="icon-checkbox"></span> ' . JText::_('JSELECT') . '</a>
							</li>
							<li><a class="uncheckall" href="javascript:;"><span class="icon-checkbox-unchecked"></span> ' . JText::_('COM_MODULES_DESELECT') . '</a>
							</li>
							<div class="nr_treeselect-menu-expand">
								<li class="divider"></li>
								<li><a class="expandall" href="javascript:;"><span class="icon-plus"></span> ' . JText::_('NR_EXPAND') . '</a></li>
								<li><a class="collapseall" href="javascript:;"><span class="icon-minus"></span> ' . JText::_('NR_COLLAPSE') . '</a></li>
							</div>
						</ul>
					</div>
				</div>
			</div>';
		$html[] = '</div>';

		$html = implode('', $html);
		return $html;
	}
}
