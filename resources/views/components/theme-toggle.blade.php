<div class="flex place-items-center">
    <a href="/theme-toggle">
        <button
{{--            type="submit"--}}
            x-data @click="$store.lightMode.toggle()"
            {{$attributes->class(['rounded-full', 'border', 'border-black', 'bg-bgCard'])}}
        ></button>
    </a>
</div>
