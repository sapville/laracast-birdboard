<div class="flex place-items-center">
    <button
        {{$attributes->class(['rounded-full', 'border', 'border-black', 'bg-bgCard'])}}
        x-data @click="$store.lightMode.toggle()"

    />
</div>
