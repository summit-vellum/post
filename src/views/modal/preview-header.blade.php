<button class="close pull-left close-modal mt-2" type="button" close-modal>×</button>
<a href="{{ $link ?? '' }}?preview=1" target="_blank" class="pull-right mt-2"><strong>Preview on Full Site »</strong></a>
<div class="text-center">
	<a href="#platform-mobile" class="btn btn-preview-platform selected" >
		@icon(['icon' => 'preview-mobile', 'iconModule' => $module])
    </a>
    <a href="#platform-desktop" class="btn btn-preview-platform" >
    	@icon(['icon' => 'preview-desktop', 'iconModule' => $module])
    </a>
</div>
