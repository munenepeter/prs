@props(
[
'type' => 'default',
'close' => false,
'message',
'icon' => 'circle-info'
]
)

<div {{ $attributes->merge(['class' => 'uk-border-rounded uk-alert uk-alert-'.$type]) }} uk-alert>
    @if ($close)
        <a class="uk-alert-close"
           uk-close
        ></a>
    @endif
    <div class="uk-grid uk-grid-small uk-flex-middle"
         uk-grid
    >
        <div class="uk-width-auto">
            @if ($icon)
                <span class="uk-icon uk-text-{{ $type }}"
                      uk-icon="icon: {{ $icon }}"
                ></span>
            @endif
        </div>

        <div class="uk-width-expand">
            @if ($message)
                <p>{{ $message }}</p>
            @endif
            {{ $slot }}
        </div>
    </div>

</div>
