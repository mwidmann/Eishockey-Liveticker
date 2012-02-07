<?php /* Smarty version 2.6.22, created on 2010-12-29 13:50:01
         compiled from /Users/mwidmann/dropbox/projects/liveticker/templates/admin/match_list.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('modifier', 'count', '/Users/mwidmann/dropbox/projects/liveticker/templates/admin/match_list.tpl', 3, false),array('modifier', 'date_format', '/Users/mwidmann/dropbox/projects/liveticker/templates/admin/match_list.tpl', 21, false),)), $this); ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "./admin/admin_header.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
		<div class="msg"><?php echo $this->_tpl_vars['msg']; ?>
</div>
		<?php if (count($this->_tpl_vars['matches']) == 0): ?>
			Keine Spiele vorhanden - 
		<?php else: ?>
			<?php echo count($this->_tpl_vars['matches']); ?>
  Matches gefunden -
		<?php endif; ?>
		<a href="match_edit.php">neues Match erstellen</a>

		<ul>
		<?php unset($this->_sections['idx']);
$this->_sections['idx']['name'] = 'idx';
$this->_sections['idx']['loop'] = is_array($_loop=$this->_tpl_vars['matches']) ? count($_loop) : max(0, (int)$_loop); unset($_loop);
$this->_sections['idx']['show'] = true;
$this->_sections['idx']['max'] = $this->_sections['idx']['loop'];
$this->_sections['idx']['step'] = 1;
$this->_sections['idx']['start'] = $this->_sections['idx']['step'] > 0 ? 0 : $this->_sections['idx']['loop']-1;
if ($this->_sections['idx']['show']) {
    $this->_sections['idx']['total'] = $this->_sections['idx']['loop'];
    if ($this->_sections['idx']['total'] == 0)
        $this->_sections['idx']['show'] = false;
} else
    $this->_sections['idx']['total'] = 0;
if ($this->_sections['idx']['show']):

            for ($this->_sections['idx']['index'] = $this->_sections['idx']['start'], $this->_sections['idx']['iteration'] = 1;
                 $this->_sections['idx']['iteration'] <= $this->_sections['idx']['total'];
                 $this->_sections['idx']['index'] += $this->_sections['idx']['step'], $this->_sections['idx']['iteration']++):
$this->_sections['idx']['rownum'] = $this->_sections['idx']['iteration'];
$this->_sections['idx']['index_prev'] = $this->_sections['idx']['index'] - $this->_sections['idx']['step'];
$this->_sections['idx']['index_next'] = $this->_sections['idx']['index'] + $this->_sections['idx']['step'];
$this->_sections['idx']['first']      = ($this->_sections['idx']['iteration'] == 1);
$this->_sections['idx']['last']       = ($this->_sections['idx']['iteration'] == $this->_sections['idx']['total']);
?>

			<?php $this->assign('entry', $this->_tpl_vars['matches'][$this->_sections['idx']['index']]); ?>
			<li>

				<?php if ($this->_tpl_vars['entry']->running): ?>
					<span style="color: #009900; font-weight: bold">AKTIV</span>
				<?php else: ?>
					<span style="color: #990000; font-weight: bold">INAKTIV</span>
				<?php endif; ?>
				<?php echo ((is_array($_tmp=$this->_tpl_vars['entry']->matchdate)) ? $this->_run_mod_handler('date_format', true, $_tmp, "%d.%m.%Y %H:%M") : smarty_modifier_date_format($_tmp, "%d.%m.%Y %H:%M")); ?>

				<b><?php echo $this->_tpl_vars['entry']->home_name; ?>
</b> gg. <b><?php echo $this->_tpl_vars['entry']->away_name; ?>
</b>
				[<a href="match_edit.php?id=<?php echo $this->_tpl_vars['entry']->match_id; ?>
">editieren</a>] -
				[<a href="liveticker.php?id=<?php echo $this->_tpl_vars['entry']->match_id; ?>
&home_name=<?php echo $this->_tpl_vars['entry']->home_name; ?>
&away_name=<?php echo $this->_tpl_vars['entry']->away_name; ?>
">liveticker</a>] -
				[<a href="../?id=<?php echo $this->_tpl_vars['entry']->match_id; ?>
" target="_new">öffentliche URL zum Liveticker</a>]

			</li>

		<?php endfor; endif; ?>
		</ul>
	</body>
</html>
