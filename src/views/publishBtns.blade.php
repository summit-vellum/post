<li>
	@php
		$hide = (isset($data) && !empty($data)) ? '' : 'hide';
		$hide = (Session::has('flash_message')) ? 'hide' : '';
	@endphp
	@if(Session::has('flash_message'))
    <div class="mt-3" flash-message><em>{{ Session::get('flash_message') }}</em></div>
    @endif
	@button(['element'=>'button', 'color'=>'blue','label'=>'Save', 'class'=>'btn '.$hide.' btn-primary '.$module.'-submit btn-block article-post mt-2 mb-2 px-5', 'attr' => arrayToHtmlAttributes(['name'=>'submit', 'btn-post-save' => ''])])
	<img src="/images/spinner.gif" class="mt-3 hide" width="25" height="25" data-post-loader>
</li>
@if(isset($data) && !empty($data))
<li>
	@button(['link' => '#', 'label'=>'Preview', 'icon'=>'preview', 'iconClasses'=>'color-black', 'attr'=>arrayToHtmlAttributes(['data-post-preview' => '', 'data-toggle' => 'modal', 'data-target' => '#toolModal', 'data-url' => route($module.'.preview', ['id' => $data->id]).'#platform-mobile']), 'class' => 'icon-link', 'spanClass' => 'color-black text-bold'])
</li>
@endif
<li>
	@php
		$isPublishedLater = $data->is_published_later ?? false;
		$postStatus = $data->status ?? false;
	@endphp
	<div id="article-status" class="btn-group">
		<button type="button" class="btn {{ ($isPublishedLater) ? $status[3]['btn'] : $status[$postStatus]['btn'] }} mt-2 mb-1 px-5" data-toggle="dropdown" post-satus-btn>
            {{ ($isPublishedLater) ? $status[3]['name'] : $status[$postStatus]['name'] }}
            <b class="caret"></b>
        </button>

        <ul class="dropdown-menu datepicker-container">
        	<div class="row align-items-center px-3 py-3">
                <div class="col-md-8">
                    <div><strong>Set as Draft</strong></div>
                    <small class="d-block">Content not visible on live site</small>
                </div>
                <div class="col-md-4">
                	@button(['element'=>'button', 'label'=>'Draft', 'class'=>'btn btn-gray btn-block '.$module.'-submit', 'attr'=>arrayToHtmlAttributes(['data-status' => $status[0]['id']])])
                </div>
            </div>

            @if($postStatus == 0 || $isPublishedLater)
            <hr class="mt-1 mb-1">
            <div class="row align-items-center px-3 py-3">
            	<div class="col-md-8">
                    <div><strong>Publish</strong></div>
                    <small class="d-block">Push your content on live site</small>
                </div>
                <div class="col-md-4">
                	@button(['element'=>'button', 'label'=>'Publish now', 'class'=>'btn btn-primary btn-block '.$module.'-submit', 'attr'=>arrayToHtmlAttributes(['data-status' => $status[1]['id'], 'data-publish' => 'now'])])
                </div>
                <div class="col-md-8">
                    <div class="d-block">
                       @yield('publishDate')
                    </div>
                </div>

                <div class="col-md-4">
                	@button(['element'=>'button', 'label'=>'Publish Later', 'class'=>'btn btn-outline-primary btn-block publish-later '.$module.'-submit', 'attr'=>arrayToHtmlAttributes(['data-status' => $status[3]['id']])])
                </div>
            </div>
            @endif

            @if(isset($data) && !empty($data))
            <hr class="mt-1 mb-1">
            <div class="row align-items-center px-3 py-3">
            	<div class="col-md-8">
                    <div><strong>Disable Entry</strong></div>
                    <small class="d-block">Content no longer be accessed</small>
                </div>
                <div class="col-md-4">
                	@php
                		$deleteDialogNotif = $moduleConfig['delete_dialog_notif'];
                		$subText = isset($deleteDialogNotif['valueDisplayedIn']['subText']) ? $data[$deleteDialogNotif['valueDisplayedIn']['subText']] : '';
                		$subText = isset($deleteDialogNotif['valueDisplayedIn']['preSubText']) ? $deleteDialogNotif['valueDisplayedIn']['preSubText'].' '.$subText : $subText;
                	@endphp
                	@button(['element'=>'button', 'label'=>'Disable', 'class'=>'btn btn-danger btn-block '.$module.'-submit', 'attr'=>arrayToHtmlAttributes([
                								   'data-status' => $status[2]['id'],
                								   'data-toggle' => 'modal',
                								   'data-target' => '#deleteResourceDialog',
                								   'data-ajax-modal' => '{"items":{"title":"'.htmlspecialchars($data[$deleteDialogNotif['valueDisplayedIn']['title']], ENT_QUOTES, 'UTF-8').'","header":"'.$deleteDialogNotif['header'].'","dismiss":"'.$deleteDialogNotif['dismiss'].'","continue":"'.$deleteDialogNotif['continue'].'","subtext":"'.$subText.'"},"params":{"url":"'.url($module, $data['id']).'","type":"DELETE","callback":"directToUrl","link":"'.route($module.'.index').'"}}'
                								])])
                </div>
            </div>
            @endif
        </ul>
	</div>
</li>
