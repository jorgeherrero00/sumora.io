<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" style="scroll-behavior: smooth;">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sumora | Transforma tus reuniones</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <link rel="icon" href="{{ asset('logos/logo.png') }}" type="image/png">

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */@layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-serif:ui-serif,Georgia,Cambria,"Times New Roman",Times,serif;--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;--color-red-50:oklch(.971 .013 17.38);--color-red-100:oklch(.936 .032 17.717);--color-red-200:oklch(.885 .062 18.334);--color-red-300:oklch(.808 .114 19.571);--color-red-400:oklch(.704 .191 22.216);--color-red-500:oklch(.637 .237 25.331);--color-red-600:oklch(.577 .245 27.325);--color-red-700:oklch(.505 .213 27.518);--color-red-800:oklch(.444 .177 26.899);--color-red-900:oklch(.396 .141 25.723);--color-red-950:oklch(.258 .092 26.042);--color-orange-50:oklch(.98 .016 73.684);--color-orange-100:oklch(.954 .038 75.164);--color-orange-200:oklch(.901 .076 70.697);--color-orange-300:oklch(.837 .128 66.29);--color-orange-400:oklch(.75 .183 55.934);--color-orange-500:oklch(.705 .213 47.604);--color-orange-600:oklch(.646 .222 41.116);--color-orange-700:oklch(.553 .195 38.402);--color-orange-800:oklch(.47 .157 37.304);--color-orange-900:oklch(.408 .123 38.172);--color-orange-950:oklch(.266 .079 36.259);--color-amber-50:oklch(.987 .022 95.277);--color-amber-100:oklch(.962 .059 95.617);--color-amber-200:oklch(.924 .12 95.746);--color-amber-300:oklch(.879 .169 91.605);--color-amber-400:oklch(.828 .189 84.429);--color-amber-500:oklch(.769 .188 70.08);--color-amber-600:oklch(.666 .179 58.318);--color-amber-700:oklch(.555 .163 48.998);--color-amber-800:oklch(.473 .137 46.201);--color-amber-900:oklch(.414 .112 45.904);--color-amber-950:oklch(.279 .077 45.635);--color-yellow-50:oklch(.987 .026 102.212);--color-yellow-100:oklch(.973 .071 103.193);--color-yellow-200:oklch(.945 .129 101.54);--color-yellow-300:oklch(.905 .182 98.111);--color-yellow-400:oklch(.852 .199 91.936);--color-yellow-500:oklch(.795 .184 86.047);--color-yellow-600:oklch(.681 .162 75.834);--color-yellow-700:oklch(.554 .135 66.442);--color-yellow-800:oklch(.476 .114 61.907);--color-yellow-900:oklch(.421 .095 57.708);--color-yellow-950:oklch(.286 .066 53.813);--color-lime-50:oklch(.986 .031 120.757);--color-lime-100:oklch(.967 .067 122.328);--color-lime-200:oklch(.938 .127 124.321);--color-lime-300:oklch(.897 .196 126.665);--color-lime-400:oklch(.841 .238 128.85);--color-lime-500:oklch(.768 .233 130.85);--color-lime-600:oklch(.648 .2 131.684);--color-lime-700:oklch(.532 .157 131.589);--color-lime-800:oklch(.453 .124 130.933);--color-lime-900:oklch(.405 .101 131.063);--color-lime-950:oklch(.274 .072 132.109);--color-green-50:oklch(.982 .018 155.826);--color-green-100:oklch(.962 .044 156.743);--color-green-200:oklch(.925 .084 155.995);--color-green-300:oklch(.871 .15 154.449);--color-green-400:oklch(.792 .209 151.711);--color-green-500:oklch(.723 .219 149.579);--color-green-600:oklch(.627 .194 149.214);--color-green-700:oklch(.527 .154 150.069);--color-green-800:oklch(.448 .119 151.328);--color-green-900:oklch(.393 .095 152.535);--color-green-950:oklch(.266 .065 152.934);--color-emerald-50:oklch(.979 .021 166.113);--color-emerald-100:oklch(.95 .052 163.051);--color-emerald-200:oklch(.905 .093 164.15);--color-emerald-300:oklch(.845 .143 164.978);--color-emerald-400:oklch(.765 .177 163.223);--color-emerald-500:oklch(.696 .17 162.48);--color-emerald-600:oklch(.596 .145 163.225);--color-emerald-700:oklch(.508 .118 165.612);--color-emerald-800:oklch(.432 .095 166.913);--color-emerald-900:oklch(.378 .077 168.94);--color-emerald-950:oklch(.262 .051 172.552);--color-teal-50:oklch(.984 .014 180.72);--color-teal-100:oklch(.953 .051 180.801);--color-teal-200:oklch(.91 .096 180.426);--color-teal-300:oklch(.855 .138 181.071);--color-teal-400:oklch(.777 .152 181.912);--color-teal-500:oklch(.704 .14 182.503);--color-teal-600:oklch(.6 .118 184.704);--color-teal-700:oklch(.511 .096 186.391);--color-teal-800:oklch(.437 .078 188.216);--color-teal-900:oklch(.386 .063 188.416);--color-teal-950:oklch(.277 .046 192.524);--color-cyan-50:oklch(.984 .019 200.873);--color-cyan-100:oklch(.956 .045 203.388);--color-cyan-200:oklch(.917 .08 205.041);--color-cyan-300:oklch(.865 .127 207.078);--color-cyan-400:oklch(.789 .154 211.53);--color-cyan-500:oklch(.715 .143 215.221);--color-cyan-600:oklch(.609 .126 221.723);--color-cyan-700:oklch(.52 .105 223.128);--color-cyan-800:oklch(.45 .085 224.283);--color-cyan-900:oklch(.398 .07 227.392);--color-cyan-950:oklch(.302 .056 229.695);--color-sky-50:oklch(.977 .013 236.62);--color-sky-100:oklch(.951 .026 236.824);--color-sky-200:oklch(.901 .058 230.902);--color-sky-300:oklch(.828 .111 230.318);--color-sky-400:oklch(.746 .16 232.661);--color-sky-500:oklch(.685 .169 237.323);--color-sky-600:oklch(.588 .158 241.966);--color-sky-700:oklch(.5 .134 242.749);--color-sky-800:oklch(.443 .11 240.79);--color-sky-900:oklch(.391 .09 240.876);--color-sky-950:oklch(.293 .066 243.157);--color-blue-50:oklch(.97 .014 254.604);--color-blue-100:oklch(.932 .032 255.585);--color-blue-200:oklch(.882 .059 254.128);--color-blue-300:oklch(.809 .105 251.813);--color-blue-400:oklch(.707 .165 254.624);--color-blue-500:oklch(.623 .214 259.815);--color-blue-600:oklch(.546 .245 262.881);--color-blue-700:oklch(.488 .243 264.376);--color-blue-800:oklch(.424 .199 265.638);--color-blue-900:oklch(.379 .146 265.522);--color-blue-950:oklch(.282 .091 267.935);--color-indigo-50:oklch(.962 .018 272.314);--color-indigo-100:oklch(.93 .034 272.788);--color-indigo-200:oklch(.87 .065 274.039);--color-indigo-300:oklch(.785 .115 274.713);--color-indigo-400:oklch(.673 .182 276.935);--color-indigo-500:oklch(.585 .233 277.117);--color-indigo-600:oklch(.511 .262 276.966);--color-indigo-700:oklch(.457 .24 277.023);--color-indigo-800:oklch(.398 .195 277.366);--color-indigo-900:oklch(.359 .144 278.697);--color-indigo-950:oklch(.257 .09 281.288);--color-violet-50:oklch(.969 .016 293.756);--color-violet-100:oklch(.943 .029 294.588);--color-violet-200:oklch(.894 .057 293.283);--color-violet-300:oklch(.811 .111 293.571);--color-violet-400:oklch(.702 .183 293.541);--color-violet-500:oklch(.606 .25 292.717);--color-violet-600:oklch(.541 .281 293.009);--color-violet-700:oklch(.491 .27 292.581);--color-violet-800:oklch(.432 .232 292.759);--color-violet-900:oklch(.38 .189 293.745);--color-violet-950:oklch(.283 .141 291.089);--color-purple-50:oklch(.977 .014 308.299);--color-purple-100:oklch(.946 .033 307.174);--color-purple-200:oklch(.902 .063 306.703);--color-purple-300:oklch(.827 .119 306.383);--color-purple-400:oklch(.714 .203 305.504);--color-purple-500:oklch(.627 .265 303.9);--color-purple-600:oklch(.558 .288 302.321);--color-purple-700:oklch(.496 .265 301.924);--color-purple-800:oklch(.438 .218 303.724);--color-purple-900:oklch(.381 .176 304.987);--color-purple-950:oklch(.291 .149 302.717);--color-fuchsia-50:oklch(.977 .017 320.058);--color-fuchsia-100:oklch(.952 .037 318.852);--color-fuchsia-200:oklch(.903 .076 319.62);--color-fuchsia-300:oklch(.833 .145 321.434);--color-fuchsia-400:oklch(.74 .238 322.16);--color-fuchsia-500:oklch(.667 .295 322.15);--color-fuchsia-600:oklch(.591 .293 322.896);--color-fuchsia-700:oklch(.518 .253 323.949);--color-fuchsia-800:oklch(.452 .211 324.591);--color-fuchsia-900:oklch(.401 .17 325.612);--color-fuchsia-950:oklch(.293 .136 325.661);--color-pink-50:oklch(.971 .014 343.198);--color-pink-100:oklch(.948 .028 342.258);--color-pink-200:oklch(.899 .061 343.231);--color-pink-300:oklch(.823 .12 346.018);--color-pink-400:oklch(.718 .202 349.761);--color-pink-500:oklch(.656 .241 354.308);--color-pink-600:oklch(.592 .249 .584);--color-pink-700:oklch(.525 .223 3.958);--color-pink-800:oklch(.459 .187 3.815);--color-pink-900:oklch(.408 .153 2.432);--color-pink-950:oklch(.284 .109 3.907);--color-rose-50:oklch(.969 .015 12.422);--color-rose-100:oklch(.941 .03 12.58);--color-rose-200:oklch(.892 .058 10.001);--color-rose-300:oklch(.81 .117 11.638);--color-rose-400:oklch(.712 .194 13.428);--color-rose-500:oklch(.645 .246 16.439);--color-rose-600:oklch(.586 .253 17.585);--color-rose-700:oklch(.514 .222 16.935);--color-rose-800:oklch(.455 .188 13.697);--color-rose-900:oklch(.41 .159 10.272);--color-rose-950:oklch(.271 .105 12.094);--color-slate-50:oklch(.984 .003 247.858);--color-slate-100:oklch(.968 .007 247.896);--color-slate-200:oklch(.929 .013 255.508);--color-slate-300:oklch(.869 .022 252.894);--color-slate-400:oklch(.704 .04 256.788);--color-slate-500:oklch(.554 .046 257.417);--color-slate-600:oklch(.446 .043 257.281);--color-slate-700:oklch(.372 .044 257.287);--color-slate-800:oklch(.279 .041 260.031);--color-slate-900:oklch(.208 .042 265.755);--color-slate-950:oklch(.129 .042 264.695);--color-gray-50:oklch(.985 .002 247.839);--color-gray-100:oklch(.967 .003 264.542);--color-gray-200:oklch(.928 .006 264.531);--color-gray-300:oklch(.872 .01 258.338);--color-gray-400:oklch(.707 .022 261.325);--color-gray-500:oklch(.551 .027 264.364);--color-gray-600:oklch(.446 .03 256.802);--color-gray-700:oklch(.373 .034 259.733);--color-gray-800:oklch(.278 .033 256.848);--color-gray-900:oklch(.21 .034 264.665);--color-gray-950:oklch(.13 .028 261.692);--color-zinc-50:oklch(.985 0 0);--color-zinc-100:oklch(.967 .001 286.375);--color-zinc-200:oklch(.92 .004 286.32);--color-zinc-300:oklch(.871 .006 286.286);--color-zinc-400:oklch(.705 .015 286.067);--color-zinc-500:oklch(.552 .016 285.938);--color-zinc-600:oklch(.442 .017 285.786);--color-zinc-700:oklch(.37 .013 285.805);--color-zinc-800:oklch(.274 .006 286.033);--color-zinc-900:oklch(.21 .006 285.885);--color-zinc-950:oklch(.141 .005 285.823);--color-neutral-50:oklch(.985 0 0);--color-neutral-100:oklch(.97 0 0);--color-neutral-200:oklch(.922 0 0);--color-neutral-300:oklch(.87 0 0);--color-neutral-400:oklch(.708 0 0);--color-neutral-500:oklch(.556 0 0);--color-neutral-600:oklch(.439 0 0);--color-neutral-700:oklch(.371 0 0);--color-neutral-800:oklch(.269 0 0);--color-neutral-900:oklch(.205 0 0);--color-neutral-950:oklch(.145 0 0);--color-stone-50:oklch(.985 .001 106.423);--color-stone-100:oklch(.97 .001 106.424);--color-stone-200:oklch(.923 .003 48.717);--color-stone-300:oklch(.869 .005 56.366);--color-stone-400:oklch(.709 .01 56.259);--color-stone-500:oklch(.553 .013 58.071);--color-stone-600:oklch(.444 .011 73.639);--color-stone-700:oklch(.374 .01 67.558);--color-stone-800:oklch(.268 .007 34.298);--color-stone-900:oklch(.216 .006 56.043);--color-stone-950:oklch(.147 .004 49.25);--color-black:#000;--color-white:#fff;--spacing:.25rem;--breakpoint-sm:40rem;--breakpoint-md:48rem;--breakpoint-lg:64rem;--breakpoint-xl:80rem;--breakpoint-2xl:96rem;--container-3xs:16rem;--container-2xs:18rem;--container-xs:20rem;--container-sm:24rem;--container-md:28rem;--container-lg:32rem;--container-xl:36rem;--container-2xl:42rem;--container-3xl:48rem;--container-4xl:56rem;--container-5xl:64rem;--container-6xl:72rem;--container-7xl:80rem;--text-xs:.75rem;--text-xs--line-height:calc(1/.75);--text-sm:.875rem;--text-sm--line-height:calc(1.25/.875);--text-base:1rem;--text-base--line-height: 1.5 ;--text-lg:1.125rem;--text-lg--line-height:calc(1.75/1.125);--text-xl:1.25rem;--text-xl--line-height:calc(1.75/1.25);--text-2xl:1.5rem;--text-2xl--line-height:calc(2/1.5);--text-3xl:1.875rem;--text-3xl--line-height: 1.2 ;--text-4xl:2.25rem;--text-4xl--line-height:calc(2.5/2.25);--text-5xl:3rem;--text-5xl--line-height:1;--text-6xl:3.75rem;--text-6xl--line-height:1;--text-7xl:4.5rem;--text-7xl--line-height:1;--text-8xl:6rem;--text-8xl--line-height:1;--text-9xl:8rem;--text-9xl--line-height:1;--font-weight-thin:100;--font-weight-extralight:200;--font-weight-light:300;--font-weight-normal:400;--font-weight-medium:500;--font-weight-semibold:600;--font-weight-bold:700;--font-weight-extrabold:800;--font-weight-black:900;--tracking-tighter:-.05em;--tracking-tight:-.025em;--tracking-normal:0em;--tracking-wide:.025em;--tracking-wider:.05em;--tracking-widest:.1em;--leading-tight:1.25;--leading-snug:1.375;--leading-normal:1.5;--leading-relaxed:1.625;--leading-loose:2;--radius-xs:.125rem;--radius-sm:.25rem;--radius-md:.375rem;--radius-lg:.5rem;--radius-xl:.75rem;--radius-2xl:1rem;--radius-3xl:1.5rem;--radius-4xl:2rem;--shadow-2xs:0 1px #0000000d;--shadow-xs:0 1px 2px 0 #0000000d;--shadow-sm:0 1px 3px 0 #0000001a,0 1px 2px -1px #0000001a;--shadow-md:0 4px 6px -1px #0000001a,0 2px 4px -2px #0000001a;--shadow-lg:0 10px 15px -3px #0000001a,0 4px 6px -4px #0000001a;--shadow-xl:0 20px 25px -5px #0000001a,0 8px 10px -6px #0000001a;--shadow-2xl:0 25px 50px -12px #00000040;--inset-shadow-2xs:inset 0 1px #0000000d;--inset-shadow-xs:inset 0 1px 1px #0000000d;--inset-shadow-sm:inset 0 2px 4px #0000000d;--drop-shadow-xs:0 1px 1px #0000000d;--drop-shadow-sm:0 1px 2px #00000026;--drop-shadow-md:0 3px 3px #0000001f;--drop-shadow-lg:0 4px 4px #00000026;--drop-shadow-xl:0 9px 7px #0000001a;--drop-shadow-2xl:0 25px 25px #00000026;--ease-in:cubic-bezier(.4,0,1,1);--ease-out:cubic-bezier(0,0,.2,1);--ease-in-out:cubic-bezier(.4,0,.2,1);--animate-spin:spin 1s linear infinite;--animate-ping:ping 1s cubic-bezier(0,0,.2,1)infinite;--animate-pulse:pulse 2s cubic-bezier(.4,0,.6,1)infinite;--animate-bounce:bounce 1s infinite;--blur-xs:4px;--blur-sm:8px;--blur-md:12px;--blur-lg:16px;--blur-xl:24px;--blur-2xl:40px;--blur-3xl:64px;--perspective-dramatic:100px;--perspective-near:300px;--perspective-normal:500px;--perspective-midrange:800px;--perspective-distant:1200px;--aspect-video:16/9;--default-transition-duration:.15s;--default-transition-timing-function:cubic-bezier(.4,0,.2,1);--default-font-family:var(--font-sans);--default-font-feature-settings:var(--font-sans--font-feature-settings);--default-font-variation-settings:var(--font-sans--font-variation-settings);--default-mono-font-family:var(--font-mono);--default-mono-font-feature-settings:var(--font-mono--font-feature-settings);--default-mono-font-variation-settings:var(--font-mono--font-variation-settings)}}@layer base{*,:after,:before,::backdrop{box-sizing:border-box;border:0 solid;margin:0;padding:0}::file-selector-button{box-sizing:border-box;border:0 solid;margin:0;padding:0}html,:host{-webkit-text-size-adjust:100%;-moz-tab-size:4;tab-size:4;line-height:1.5;font-family:var(--default-font-family,ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji");font-feature-settings:var(--default-font-feature-settings,normal);font-variation-settings:var(--default-font-variation-settings,normal);-webkit-tap-highlight-color:transparent}body{line-height:inherit}hr{height:0;color:inherit;border-top-width:1px}abbr:where([title]){-webkit-text-decoration:underline dotted;text-decoration:underline dotted}h1,h2,h3,h4,h5,h6{font-size:inherit;font-weight:inherit}a{color:inherit;-webkit-text-decoration:inherit;text-decoration:inherit}b,strong{font-weight:bolder}code,kbd,samp,pre{font-family:var(--default-mono-font-family,ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace);font-feature-settings:var(--default-mono-font-feature-settings,normal);font-variation-settings:var(--default-mono-font-variation-settings,normal);font-size:1em}small{font-size:80%}sub,sup{vertical-align:baseline;font-size:75%;line-height:0;position:relative}sub{bottom:-.25em}sup{top:-.5em}table{text-indent:0;border-color:inherit;border-collapse:collapse}:-moz-focusring{outline:auto}progress{vertical-align:baseline}summary{display:list-item}ol,ul,menu{list-style:none}img,svg,video,canvas,audio,iframe,embed,object{vertical-align:middle;display:block}img,video{max-width:100%;height:auto}button,input,select,optgroup,textarea{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}::file-selector-button{font:inherit;font-feature-settings:inherit;font-variation-settings:inherit;letter-spacing:inherit;color:inherit;opacity:1;background-color:#0000;border-radius:0}:where(select:is([multiple],[size])) optgroup{font-weight:bolder}:where(select:is([multiple],[size])) optgroup option{padding-inline-start:20px}::file-selector-button{margin-inline-end:4px}::placeholder{opacity:1;color:color-mix(in oklab,currentColor 50%,transparent)}textarea{resize:vertical}::-webkit-search-decoration{-webkit-appearance:none}::-webkit-date-and-time-value{min-height:1lh;text-align:inherit}::-webkit-datetime-edit{display:inline-flex}::-webkit-datetime-edit-fields-wrapper{padding:0}::-webkit-datetime-edit{padding-block:0}::-webkit-datetime-edit-year-field{padding-block:0}::-webkit-datetime-edit-month-field{padding-block:0}::-webkit-datetime-edit-day-field{padding-block:0}::-webkit-datetime-edit-hour-field{padding-block:0}::-webkit-datetime-edit-minute-field{padding-block:0}::-webkit-datetime-edit-second-field{padding-block:0}::-webkit-datetime-edit-millisecond-field{padding-block:0}::-webkit-datetime-edit-meridiem-field{padding-block:0}:-moz-ui-invalid{box-shadow:none}button,input:where([type=button],[type=reset],[type=submit]){-webkit-appearance:button;-moz-appearance:button;appearance:button}::file-selector-button{-webkit-appearance:button;-moz-appearance:button;appearance:button}::-webkit-inner-spin-button{height:auto}::-webkit-outer-spin-button{height:auto}[hidden]:where(:not([hidden=until-found])){display:none!important}}@layer components;@layer utilities{.absolute{position:absolute}.relative{position:relative}.static{position:static}.inset-0{inset:calc(var(--spacing)*0)}.-mt-\[4\.9rem\]{margin-top:-4.9rem}.-mb-px{margin-bottom:-1px}.mb-1{margin-bottom:calc(var(--spacing)*1)}.mb-2{margin-bottom:calc(var(--spacing)*2)}.mb-4{margin-bottom:calc(var(--spacing)*4)}.mb-6{margin-bottom:calc(var(--spacing)*6)}.-ml-8{margin-left:calc(var(--spacing)*-8)}.flex{display:flex}.hidden{display:none}.inline-block{display:inline-block}.inline-flex{display:inline-flex}.table{display:table}.aspect-\[335\/376\]{aspect-ratio:335/376}.h-1{height:calc(var(--spacing)*1)}.h-1\.5{height:calc(var(--spacing)*1.5)}.h-2{height:calc(var(--spacing)*2)}.h-2\.5{height:calc(var(--spacing)*2.5)}.h-3{height:calc(var(--spacing)*3)}.h-3\.5{height:calc(var(--spacing)*3.5)}.h-14{height:calc(var(--spacing)*14)}.h-14\.5{height:calc(var(--spacing)*14.5)}.min-h-screen{min-height:100vh}.w-1{width:calc(var(--spacing)*1)}.w-1\.5{width:calc(var(--spacing)*1.5)}.w-2{width:calc(var(--spacing)*2)}.w-2\.5{width:calc(var(--spacing)*2.5)}.w-3{width:calc(var(--spacing)*3)}.w-3\.5{width:calc(var(--spacing)*3.5)}.w-\[448px\]{width:448px}.w-full{width:100%}.max-w-\[335px\]{max-width:335px}.max-w-none{max-width:none}.flex-1{flex:1}.shrink-0{flex-shrink:0}.translate-y-0{--tw-translate-y:calc(var(--spacing)*0);translate:var(--tw-translate-x)var(--tw-translate-y)}.transform{transform:var(--tw-rotate-x)var(--tw-rotate-y)var(--tw-rotate-z)var(--tw-skew-x)var(--tw-skew-y)}.flex-col{flex-direction:column}.flex-col-reverse{flex-direction:column-reverse}.items-center{align-items:center}.justify-center{justify-content:center}.justify-end{justify-content:flex-end}.gap-3{gap:calc(var(--spacing)*3)}.gap-4{gap:calc(var(--spacing)*4)}:where(.space-x-1>:not(:last-child)){--tw-space-x-reverse:0;margin-inline-start:calc(calc(var(--spacing)*1)*var(--tw-space-x-reverse));margin-inline-end:calc(calc(var(--spacing)*1)*calc(1 - var(--tw-space-x-reverse)))}.overflow-hidden{overflow:hidden}.rounded-full{border-radius:3.40282e38px}.rounded-sm{border-radius:var(--radius-sm)}.rounded-t-lg{border-top-left-radius:var(--radius-lg);border-top-right-radius:var(--radius-lg)}.rounded-br-lg{border-bottom-right-radius:var(--radius-lg)}.rounded-bl-lg{border-bottom-left-radius:var(--radius-lg)}.border{border-style:var(--tw-border-style);border-width:1px}.border-\[\#19140035\]{border-color:#19140035}.border-\[\#e3e3e0\]{border-color:#e3e3e0}.border-black{border-color:var(--color-black)}.border-transparent{border-color:#0000}.bg-\[\#1b1b18\]{background-color:#1b1b18}.bg-\[\#FDFDFC\]{background-color:#fdfdfc}.bg-\[\#dbdbd7\]{background-color:#dbdbd7}.bg-\[\#fff2f2\]{background-color:#fff2f2}.bg-white{background-color:var(--color-white)}.p-6{padding:calc(var(--spacing)*6)}.px-5{padding-inline:calc(var(--spacing)*5)}.py-1{padding-block:calc(var(--spacing)*1)}.py-1\.5{padding-block:calc(var(--spacing)*1.5)}.py-2{padding-block:calc(var(--spacing)*2)}.pb-12{padding-bottom:calc(var(--spacing)*12)}.text-sm{font-size:var(--text-sm);line-height:var(--tw-leading,var(--text-sm--line-height))}.text-\[13px\]{font-size:13px}.leading-\[20px\]{--tw-leading:20px;line-height:20px}.leading-normal{--tw-leading:var(--leading-normal);line-height:var(--leading-normal)}.font-medium{--tw-font-weight:var(--font-weight-medium);font-weight:var(--font-weight-medium)}.text-\[\#1b1b18\]{color:#1b1b18}.text-\[\#706f6c\]{color:#706f6c}.text-\[\#F53003\],.text-\[\#f53003\]{color:#f53003}.text-white{color:var(--color-white)}.underline{text-decoration-line:underline}.underline-offset-4{text-underline-offset:4px}.opacity-100{opacity:1}.shadow-\[0px_0px_1px_0px_rgba\(0\,0\,0\,0\.03\)\,0px_1px_2px_0px_rgba\(0\,0\,0\,0\.06\)\]{--tw-shadow:0px 0px 1px 0px var(--tw-shadow-color,#00000008),0px 1px 2px 0px var(--tw-shadow-color,#0000000f);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.shadow-\[inset_0px_0px_0px_1px_rgba\(26\,26\,0\,0\.16\)\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#1a1a0029);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.\!filter{filter:var(--tw-blur,)var(--tw-brightness,)var(--tw-contrast,)var(--tw-grayscale,)var(--tw-hue-rotate,)var(--tw-invert,)var(--tw-saturate,)var(--tw-sepia,)var(--tw-drop-shadow,)!important}.filter{filter:var(--tw-blur,)var(--tw-brightness,)var(--tw-contrast,)var(--tw-grayscale,)var(--tw-hue-rotate,)var(--tw-invert,)var(--tw-saturate,)var(--tw-sepia,)var(--tw-drop-shadow,)}.transition-all{transition-property:all;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.transition-opacity{transition-property:opacity;transition-timing-function:var(--tw-ease,var(--default-transition-timing-function));transition-duration:var(--tw-duration,var(--default-transition-duration))}.delay-300{transition-delay:.3s}.duration-750{--tw-duration:.75s;transition-duration:.75s}.not-has-\[nav\]\:hidden:not(:has(:is(nav))){display:none}.before\:absolute:before{content:var(--tw-content);position:absolute}.before\:top-0:before{content:var(--tw-content);top:calc(var(--spacing)*0)}.before\:top-1\/2:before{content:var(--tw-content);top:50%}.before\:bottom-0:before{content:var(--tw-content);bottom:calc(var(--spacing)*0)}.before\:bottom-1\/2:before{content:var(--tw-content);bottom:50%}.before\:left-\[0\.4rem\]:before{content:var(--tw-content);left:.4rem}.before\:border-l:before{content:var(--tw-content);border-left-style:var(--tw-border-style);border-left-width:1px}.before\:border-\[\#e3e3e0\]:before{content:var(--tw-content);border-color:#e3e3e0}@media (hover:hover){.hover\:border-\[\#1915014a\]:hover{border-color:#1915014a}.hover\:border-\[\#19140035\]:hover{border-color:#19140035}.hover\:border-black:hover{border-color:var(--color-black)}.hover\:bg-black:hover{background-color:var(--color-black)}}@media (width>=64rem){.lg\:-mt-\[6\.6rem\]{margin-top:-6.6rem}.lg\:mb-0{margin-bottom:calc(var(--spacing)*0)}.lg\:mb-6{margin-bottom:calc(var(--spacing)*6)}.lg\:-ml-px{margin-left:-1px}.lg\:ml-0{margin-left:calc(var(--spacing)*0)}.lg\:block{display:block}.lg\:aspect-auto{aspect-ratio:auto}.lg\:w-\[438px\]{width:438px}.lg\:max-w-4xl{max-width:var(--container-4xl)}.lg\:grow{flex-grow:1}.lg\:flex-row{flex-direction:row}.lg\:justify-center{justify-content:center}.lg\:rounded-t-none{border-top-left-radius:0;border-top-right-radius:0}.lg\:rounded-tl-lg{border-top-left-radius:var(--radius-lg)}.lg\:rounded-r-lg{border-top-right-radius:var(--radius-lg);border-bottom-right-radius:var(--radius-lg)}.lg\:rounded-br-none{border-bottom-right-radius:0}.lg\:p-8{padding:calc(var(--spacing)*8)}.lg\:p-20{padding:calc(var(--spacing)*20)}}@media (prefers-color-scheme:dark){.dark\:block{display:block}.dark\:hidden{display:none}.dark\:border-\[\#3E3E3A\]{border-color:#3e3e3a}.dark\:border-\[\#eeeeec\]{border-color:#eeeeec}.dark\:bg-\[\#0a0a0a\]{background-color:#0a0a0a}.dark\:bg-\[\#1D0002\]{background-color:#1d0002}.dark\:bg-\[\#3E3E3A\]{background-color:#3e3e3a}.dark\:bg-\[\#161615\]{background-color:#161615}.dark\:bg-\[\#eeeeec\]{background-color:#eeeeec}.dark\:text-\[\#1C1C1A\]{color:#1c1c1a}.dark\:text-\[\#A1A09A\]{color:#a1a09a}.dark\:text-\[\#EDEDEC\]{color:#ededec}.dark\:text-\[\#F61500\]{color:#f61500}.dark\:text-\[\#FF4433\]{color:#f43}.dark\:shadow-\[inset_0px_0px_0px_1px_\#fffaed2d\]{--tw-shadow:inset 0px 0px 0px 1px var(--tw-shadow-color,#fffaed2d);box-shadow:var(--tw-inset-shadow),var(--tw-inset-ring-shadow),var(--tw-ring-offset-shadow),var(--tw-ring-shadow),var(--tw-shadow)}.dark\:before\:border-\[\#3E3E3A\]:before{content:var(--tw-content);border-color:#3e3e3a}@media (hover:hover){.dark\:hover\:border-\[\#3E3E3A\]:hover{border-color:#3e3e3a}.dark\:hover\:border-\[\#62605b\]:hover{border-color:#62605b}.dark\:hover\:border-white:hover{border-color:var(--color-white)}.dark\:hover\:bg-white:hover{background-color:var(--color-white)}}}@starting-style{.starting\:translate-y-4{--tw-translate-y:calc(var(--spacing)*4);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:translate-y-6{--tw-translate-y:calc(var(--spacing)*6);translate:var(--tw-translate-x)var(--tw-translate-y)}}@starting-style{.starting\:opacity-0{opacity:0}}}@keyframes spin{to{transform:rotate(360deg)}}@keyframes ping{75%,to{opacity:0;transform:scale(2)}}@keyframes pulse{50%{opacity:.5}}@keyframes bounce{0%,to{animation-timing-function:cubic-bezier(.8,0,1,1);transform:translateY(-25%)}50%{animation-timing-function:cubic-bezier(0,0,.2,1);transform:none}}@property --tw-translate-x{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-y{syntax:"*";inherits:false;initial-value:0}@property --tw-translate-z{syntax:"*";inherits:false;initial-value:0}@property --tw-rotate-x{syntax:"*";inherits:false;initial-value:rotateX(0)}@property --tw-rotate-y{syntax:"*";inherits:false;initial-value:rotateY(0)}@property --tw-rotate-z{syntax:"*";inherits:false;initial-value:rotateZ(0)}@property --tw-skew-x{syntax:"*";inherits:false;initial-value:skewX(0)}@property --tw-skew-y{syntax:"*";inherits:false;initial-value:skewY(0)}@property --tw-space-x-reverse{syntax:"*";inherits:false;initial-value:0}@property --tw-border-style{syntax:"*";inherits:false;initial-value:solid}@property --tw-leading{syntax:"*";inherits:false}@property --tw-font-weight{syntax:"*";inherits:false}@property --tw-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-shadow-color{syntax:"*";inherits:false}@property --tw-inset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-shadow-color{syntax:"*";inherits:false}@property --tw-ring-color{syntax:"*";inherits:false}@property --tw-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-inset-ring-color{syntax:"*";inherits:false}@property --tw-inset-ring-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-ring-inset{syntax:"*";inherits:false}@property --tw-ring-offset-width{syntax:"<length>";inherits:false;initial-value:0}@property --tw-ring-offset-color{syntax:"*";inherits:false;initial-value:#fff}@property --tw-ring-offset-shadow{syntax:"*";inherits:false;initial-value:0 0 #0000}@property --tw-blur{syntax:"*";inherits:false}@property --tw-brightness{syntax:"*";inherits:false}@property --tw-contrast{syntax:"*";inherits:false}@property --tw-grayscale{syntax:"*";inherits:false}@property --tw-hue-rotate{syntax:"*";inherits:false}@property --tw-invert{syntax:"*";inherits:false}@property --tw-opacity{syntax:"*";inherits:false}@property --tw-saturate{syntax:"*";inherits:false}@property --tw-sepia{syntax:"*";inherits:false}@property --tw-drop-shadow{syntax:"*";inherits:false}@property --tw-duration{syntax:"*";inherits:false}@property --tw-content{syntax:"*";inherits:false;initial-value:""}
            </style>
        @endif

        <style>
        body::before {
      content: "";
      position: fixed;
      inset: 0;
      background: radial-gradient(ellipse at top left, rgba(0,0,0,0.6), transparent),
                  linear-gradient(135deg, #110f17 0%, #0b0b0f 100%);
      z-index: -1;
    }
    nav a.active {
      color: #ff5e00;
    }
    .ribbon {
      position: absolute;
      top: -0.75rem;
      right: -0.75rem;
      background: linear-gradient(45deg, #ff7a00, #ff4500);
      color: white;
      padding: 0.25rem 0.75rem;
      font-size: 0.75rem;
      font-weight: bold;
      border-radius: 0.25rem;
      transform: rotate(15deg);
    }

    .nav-link {
    position: relative;
    padding: 0.5rem 0.25rem;
    color: rgb(209 213 219); /* text-gray-300 */
    transition: color 0.3s ease;
}

.nav-link::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    height: 2px;
    width: 0;
    background: linear-gradient(to right, rgb(251 146 60), rgb(239 68 68));
    transition: all 0.3s ease;
}

.nav-link:hover {
    color: white;
}

.nav-link:hover::after {
    width: 100%;
}

.nav-link.active {
    color: white;
}

.nav-link.active::after {
    width: 100%;
}
    </style>
    </head>
<body class="bg-[#0a0a0f] text-white font-sans antialiased">
  <!-- Header -->
<header class="backdrop-blur-xl bg-black/50 fixed w-full z-20 shadow-lg shadow-orange-500/10 border-b border-orange-500/10">
    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
        <div class="flex items-center">
            <img src="{{ asset('logos/logo.png') }}" alt="Logo" class="h-10 w-10 rounded">
        </div>
        <!-- Logo with gradient effect -->
        
        <!-- Navigation with hover effects and indicator -->
        <nav class="hidden md:flex gap-8 font-medium">
            <a href="#" class="relative nav-link py-2 px-1 active">Inicio</a>
            <a href="#features" class="relative py-2 px-1 nav-link">Características</a>
            <a href="#planes" class="relative py-2 px-1 nav-link">Planes</a>
            <a href="#faq" class="relative py-2 px-1 nav-link">FAQ</a>
        </nav>
        
        <!-- Auth buttons with hover effects -->
        <div class="hidden md:flex gap-4 items-center">
            <a href="/login" class="text-sm text-gray-300 hover:text-white transition-colors duration-300 px-4 py-2">
                Iniciar sesión
            </a>
            <a href="/register" class="text-sm group relative px-5 py-2.5 overflow-hidden rounded-lg bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-md transition-all duration-300 hover:shadow-lg hover:shadow-orange-500/30 hover:-translate-y-0.5">
                <span class="relative z-10 font-medium">Regístrate</span>
                <span class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></span>
            </a>
        </div>
        
        <!-- Mobile menu button with animation -->
        <button class="md:hidden relative group">
            <div class="relative flex overflow-hidden items-center justify-center w-10 h-10">
                <div class="flex flex-col justify-between w-6 h-5 transform transition-all duration-300 origin-center group-hover:scale-110">
                    <div class="bg-white h-0.5 w-6 rounded transform transition-all duration-300 origin-left group-hover:bg-orange-400"></div>
                    <div class="bg-white h-0.5 w-6 rounded transform transition-all duration-300 group-hover:bg-orange-400"></div>
                    <div class="bg-white h-0.5 w-6 rounded transform transition-all duration-300 origin-left group-hover:bg-orange-400"></div>
                </div>
            </div>
        </button>
    </div>
    
    <!-- Mobile menu (hidden by default) -->
    <div class="md:hidden hidden bg-black/90 backdrop-blur-xl border-t border-gray-800">
        <nav class="flex flex-col py-6 px-6 space-y-4">
            <a href="#" class="py-2 nav-link pl-4">Inicio</a>
            <a href="#features" class="py-2 nav-link">Características</a>
            <a href="#planes" class="py-2 nav-link">Planes</a>
            <a href="#faq" class="py-2 nav-link">FAQ</a>
            <div class="pt-4 flex flex-col space-y-3">
                <a href="/login" class="text-center py-2 text-gray-300 hover:text-white border border-gray-700 rounded-lg hover:border-gray-500 transition-colors duration-300">Iniciar sesión</a>
                <a href="/register" class="text-center py-2 bg-gradient-to-r from-orange-500 to-red-500 text-white rounded-lg font-medium">Regístrate</a>
            </div>
        </nav>
    </div>
</header>

  <!-- Hero -->
<section class="relative min-h-screen flex flex-col justify-center pt-24 pb-20 px-6 overflow-hidden">
    <!-- Background effects -->
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/4 left-1/5 w-96 h-96 bg-orange-500/20 rounded-full filter blur-[80px] animate-pulse"></div>
        <div class="absolute bottom-1/3 right-1/4 w-80 h-80 bg-purple-600/20 rounded-full filter blur-[100px] animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 text-center max-w-5xl mx-auto">
        <div class="inline-flex items-center bg-gradient-to-r from-orange-500/30 to-red-500/30 px-4 py-2 rounded-full backdrop-blur-sm mb-6 border border-orange-500/20 shadow-lg">
            <span class="animate-pulse mr-2 h-2 w-2 bg-orange-500 rounded-full"></span>
            <span class="text-orange-300 font-medium tracking-wider text-sm">IMPULSADO POR INTELIGENCIA ARTIFICIAL</span>
        </div>
        
        <h1 class="text-5xl md:text-7xl font-extrabold mb-8 leading-tight bg-gradient-to-br from-white via-gray-100 to-gray-300 text-transparent bg-clip-text">
            Transforma tus reuniones en <span class="bg-gradient-to-r from-orange-400 to-red-500 text-transparent bg-clip-text">acciones concretas</span>
        </h1>
        
        <p class="text-xl md:text-2xl text-gray-400 max-w-3xl mx-auto mb-12 leading-relaxed">
            Sumora analiza tus conversaciones, genera resúmenes inteligentes y extrae las acciones clave para maximizar tu productividad.
        </p>
        
        <div class="flex flex-col sm:flex-row justify-center gap-6 mb-16">
            <a href="#planes" class="group relative overflow-hidden px-8 py-4 rounded-xl bg-gradient-to-r from-orange-500 to-red-500 font-bold text-lg shadow-xl shadow-orange-500/20 transform transition-all duration-300 hover:-translate-y-1 hover:shadow-orange-500/30">
                <span class="relative z-10">Prueba gratis 14 días</span>
                <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
            </a>
            <a href="#demo" class="group relative px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 font-bold text-lg transition-all duration-300 hover:bg-white/20 hover:-translate-y-1">
                <span class="flex items-center">
                    Ver demostración
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                    </svg>
                </span>
            </a>
        </div>

        <!-- Stats -->
        <div class="flex flex-wrap justify-center gap-10 text-center mb-16">
            <div class="backdrop-blur-md bg-white/5 px-6 py-4 rounded-xl border border-white/10">
                <span class="block text-3xl font-bold text-orange-400 mb-1">4,800+</span>
                <span class="text-gray-400">Usuarios activos</span>
            </div>
            <div class="backdrop-blur-md bg-white/5 px-6 py-4 rounded-xl border border-white/10">
                <span class="block text-3xl font-bold text-orange-400 mb-1">32K+</span>
                <span class="text-gray-400">Reuniones analizadas</span>
            </div>
            <div class="backdrop-blur-md bg-white/5 px-6 py-4 rounded-xl border border-white/10">
                <span class="block text-3xl font-bold text-orange-400 mb-1">98%</span>
                <span class="text-gray-400">Satisfacción</span>
            </div>
        </div>
    </div>
</section>

<!-- UI Preview with floating elements -->
<section class="relative px-6 -mt-16 z-20 mb-32">
    <div class="max-w-6xl mx-auto relative">
        <!-- Main app interface preview -->
        <div class="rounded-3xl shadow-2xl border border-gray-700/50 overflow-hidden bg-gradient-to-br from-gray-800/90 to-gray-900/90 backdrop-blur-xl transform transition-transform hover:scale-[1.01] duration-500">
            <img src="/img/ui-preview.png" alt="Vista previa de la interfaz" class="w-full object-cover" onerror="this.src='https://placehold.co/1200x600/1f1f23/orange?text=Sumora+Interface'">
        </div>       
        
        <div class="absolute -bottom-8 right-10 transform rotate-[3deg] bg-orange-500 rounded-lg shadow-xl p-3 w-48 hidden md:block">
            <div class="flex items-center gap-2 text-white">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm font-medium">Tarea completada</span>
            </div>
            <div class="mt-1">
                <div class="h-2 bg-white/30 rounded-full w-full"></div>
            </div>
        </div>
    </div>
</section>

  <!-- Features -->
<section id="features" class="py-32 px-6 bg-gradient-to-b from-black/40 to-transparent relative">
    <div class="max-w-6xl mx-auto mb-16 text-center">
        <div class="inline-block bg-orange-500/20 text-orange-300 px-3 py-1 rounded-full uppercase text-xs tracking-wider mb-6">Características</div>
        <h2 class="text-4xl md:text-5xl font-bold mb-6">Todo lo que necesitas para <span class="text-orange-500">optimizar tus reuniones</span></h2>
        <p class="text-gray-400 max-w-2xl mx-auto">Nuestras herramientas avanzadas transforman horas de conversación en acciones claras y concisas.</p>
    </div>
    
    <div class="max-w-6xl mx-auto grid md:grid-cols-3 gap-8 lg:gap-12">
        <!-- Feature 1 -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50 transform transition-all duration-300 hover:translate-y-[-8px] hover:shadow-xl hover:shadow-orange-500/10">
            <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-2xl inline-block mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">Resumen automático</h3>
            <p class="text-gray-400 mb-6">Transforma reuniones extensas en resúmenes concisos con puntos clave identificados por IA avanzada.</p>
            <ul class="text-sm text-gray-300 space-y-2">
                <li class="flex items-start">
                    <span class="text-orange-500 mr-2">✓</span>
                    Detección de temas principales
                </li>
                <li class="flex items-start">
                    <span class="text-orange-500 mr-2">✓</span>
                    Organización jerárquica
                </li>
            </ul>
        </div>
        
        <!-- Feature 2 -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50 transform transition-all duration-300 hover:translate-y-[-8px] hover:shadow-xl hover:shadow-orange-500/10">
            <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-2xl inline-block mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">Tareas destacadas</h3>
            <p class="text-gray-400 mb-6">Extracción inteligente de acciones con asignación automática y fechas límite sugeridas.</p>
            <ul class="text-sm text-gray-300 space-y-2">
                <li class="flex items-start">
                    <span class="text-orange-500 mr-2">✓</span>
                    Identificación de responsables
                </li>
                <li class="flex items-start">
                    <span class="text-orange-500 mr-2">✓</span>
                    Priorización automática
                </li>
            </ul>
        </div>
        
        <!-- Feature 3 -->
        <div class="bg-gray-800/50 backdrop-blur-sm rounded-2xl p-8 border border-gray-700/50 transform transition-all duration-300 hover:translate-y-[-8px] hover:shadow-xl hover:shadow-orange-500/10">
            <div class="bg-gradient-to-br from-orange-500 to-red-600 p-4 rounded-2xl inline-block mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
            </div>
            <h3 class="text-2xl font-bold mb-4">Integraciones avanzadas</h3>
            <p class="text-gray-400 mb-6">Conecta con tus herramientas favoritas para una gestión de tareas sin fricciones.</p>
            <ul class="text-sm text-gray-300 space-y-2">
                <li class="flex items-start">
                    <span class="text-orange-500 mr-2">✓</span>
                    Slack, Email, Notion
                </li>
                <li class="flex items-start">
                    <span class="text-orange-500 mr-2">✓</span>
                    Calendar y recordatorios
                </li>
            </ul>
        </div>
    </div>
    
    <!-- Extra feature highlight -->
    <div class="max-w-6xl mx-auto mt-20 bg-gradient-to-r from-orange-600/30 to-red-600/30 rounded-2xl p-8 md:p-12 flex flex-col md:flex-row items-center gap-10">
        <div class="md:w-1/2">
            <div class="inline-block bg-orange-500 text-white px-3 py-1 rounded-full text-xs uppercase tracking-wider mb-4">Exclusivo</div>
            <h3 class="text-3xl font-bold mb-4">Análisis de sentimiento</h3>
            <p class="text-gray-300 mb-6">Nuestra IA detecta el tono emocional de las conversaciones, destacando posibles conflictos o puntos de acuerdo.</p>
            <a href="#demo" class="inline-flex items-center text-orange-400 hover:text-orange-300 font-semibold">
                Descubre cómo funciona
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>
        <div class="md:w-1/2">
            <img src="/img/sentiment-analysis.png" alt="Análisis de sentimiento" class="rounded-xl shadow-lg w-full" onerror="this.src='https://placehold.co/600x400/orange/white?text=Análisis+de+Sentimiento'">
        </div>
    </div>
</section>

  <!-- Plans -->
<section id="planes" class="py-32 px-6 bg-gradient-to-b from-black/60 to-gray-900/80 relative">
    <!-- Background decoration elements -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden z-0">
        <div class="absolute -top-24 -left-24 w-64 h-64 bg-orange-500/20 rounded-full filter blur-3xl"></div>
        <div class="absolute top-1/2 -right-32 w-80 h-80 bg-orange-600/10 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="max-w-6xl mx-auto relative z-10">
        <div class="text-center mb-16">
            <div class="inline-block bg-orange-500/20 text-orange-300 px-3 py-1 rounded-full uppercase text-xs tracking-wider mb-4">Flexibilidad</div>
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Elige el plan <span class="text-gradient bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">perfecto para ti</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Selecciona el plan que mejor se adapte a tus necesidades, sin compromisos a largo plazo.</p>
        </div>
        
        <div class="grid md:grid-cols-3 gap-8 lg:gap-6">
            <!-- Free Plan -->
            <div class="group relative bg-gray-800/60 backdrop-blur-sm border border-gray-700/50 rounded-2xl overflow-hidden transition-all duration-300 hover:border-orange-500/30 hover:shadow-xl hover:shadow-orange-500/10 hover:translate-y-[-8px]">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-800/80 to-gray-900/80 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-white">Free</h3>
                            <p class="text-gray-400 text-sm mt-1">Para empezar a explorar</p>
                        </div>
                        <div class="bg-gray-700/50 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <span class="text-5xl font-extrabold text-white">0€</span>
                        <span class="text-gray-400 ml-2">/mes</span>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <span class="text-green-400 mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>1 reunión/mes</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-400 mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Resumen + tareas</span>
                        </li>
                        <li class="flex items-start text-gray-500">
                            <span class="text-red-400 mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Integraciones</span>
                        </li>
                    </ul>
                    
                    <a href="#" class="block w-full py-3 px-6 text-center bg-gray-700 hover:bg-orange-500 text-white font-semibold rounded-lg transition-colors duration-300">Comenzar gratis</a>
                </div>
            </div>
            
            <!-- Starter Plan (Featured) -->
            <div class="group relative bg-gradient-to-br from-orange-600/90 to-red-600/90 rounded-2xl overflow-hidden transform scale-105 shadow-[0_8px_30px_rgb(255,79,0,0.2)] z-10">
                <div class="absolute top-0 right-0 bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-bold py-1 px-4 text-xs uppercase tracking-wider transform rotate-[30deg] translate-x-[30%] translate-y-[90%]">
                    Popular
                </div>
                <div class="h-1.5 w-full bg-gradient-to-r from-yellow-300 to-orange-400"></div>
                <div class="relative z-10 p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-white">Starter</h3>
                            <p class="text-orange-100 text-sm mt-1">Ideal para profesionales</p>
                        </div>
                        <div class="bg-white/20 p-2 rounded-lg backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <span class="text-5xl font-extrabold text-white">9€</span>
                        <span class="text-orange-100 ml-2">/mes</span>
                    </div>
                    
                    <ul class="space-y-4 mb-8 text-white">
                        <li class="flex items-start">
                            <span class="text-white mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>5 reuniones/mes</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-white mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Resumen + tareas</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-white mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Integraciones básicas</span>
                        </li>
                    </ul>
                    
                    <a href="#" class="block w-full py-3 px-6 text-center bg-white text-orange-600 hover:bg-gray-100 font-semibold rounded-lg transition-colors duration-300 shadow-lg shadow-orange-600/20">Comenzar ahora</a>
                </div>
            </div>
            
            <!-- Pro Plan -->
            <div class="group relative bg-gray-800/60 backdrop-blur-sm border border-gray-700/50 rounded-2xl overflow-hidden transition-all duration-300 hover:border-orange-500/30 hover:shadow-xl hover:shadow-orange-500/10 hover:translate-y-[-8px]">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-800/80 to-gray-900/80 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                <div class="relative z-10 p-8">
                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <h3 class="text-2xl font-bold text-white">Pro</h3>
                            <p class="text-gray-400 text-sm mt-1">Para equipos y empresas</p>
                        </div>
                        <div class="bg-gray-700/50 p-2 rounded-lg">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                            </svg>
                        </div>
                    </div>
                    
                    <div class="mb-8">
                        <span class="text-5xl font-extrabold text-white">29€</span>
                        <span class="text-gray-400 ml-2">/mes</span>
                    </div>
                    
                    <ul class="space-y-4 mb-8">
                        <li class="flex items-start">
                            <span class="text-green-400 mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>20 reuniones/mes</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-400 mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Integraciones Slack, Notion, Sheets</span>
                        </li>
                        <li class="flex items-start">
                            <span class="text-green-400 mr-3 mt-1">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            <span>Prioridad soporte</span>
                        </li>
                    </ul>
                    
                    <a href="#" class="block w-full py-3 px-6 text-center bg-orange-500 hover:bg-orange-600 text-white font-semibold rounded-lg transition-colors duration-300 shadow-lg shadow-orange-600/20">Actualizar a Pro</a>
                </div>
            </div>
        </div>
        
        <!-- Enterprise callout -->
        <div class="mt-16 bg-gradient-to-r from-gray-800/50 to-gray-900/50 backdrop-blur-sm border border-gray-700/50 rounded-xl p-8 md:p-10 flex flex-col md:flex-row items-center gap-8 md:gap-16">
            <div class="md:w-2/3">
                <h3 class="text-2xl font-bold mb-3">¿Necesitas un plan personalizado?</h3>
                <p class="text-gray-400 mb-4">Contacta con nosotros para un plan Enterprise con características a medida para tu organización.</p>
                <ul class="grid grid-cols-2 gap-x-4 gap-y-2 text-sm text-gray-300">
                    <li class="flex items-center">
                        <span class="text-orange-400 mr-2">✓</span> Reuniones ilimitadas
                    </li>
                    <li class="flex items-center">
                        <span class="text-orange-400 mr-2">✓</span> API dedicada
                    </li>
                    <li class="flex items-center">
                        <span class="text-orange-400 mr-2">✓</span> SSO & Administración
                    </li>
                    <li class="flex items-center">
                        <span class="text-orange-400 mr-2">✓</span> Soporte 24/7
                    </li>
                </ul>
            </div>
            <div class="md:w-1/3 flex justify-center">
                <a href="#contact" class="inline-flex items-center py-3 px-6 rounded-lg bg-white/10 hover:bg-white/20 text-white font-medium transition-colors border border-white/20 backdrop-blur-sm">
                    <span>Contáctanos</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
    
    <style>
        .text-gradient {
            -webkit-background-clip: text;
            background-clip: text;
        }
    </style>
</section>

  <!-- CTA Bottom -->
<!-- CTA Bottom -->
<section class="py-20 px-6 relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute inset-0 bg-gradient-to-br from-orange-500/10 to-red-700/10 z-0"></div>
    <div class="absolute -top-40 -left-40 w-80 h-80 bg-orange-500/10 rounded-full filter blur-3xl"></div>
    <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-red-500/10 rounded-full filter blur-3xl"></div>
    
    <!-- Main content -->
    <div class="max-w-4xl mx-auto bg-gradient-to-r from-gray-900/80 to-gray-800/80 p-10 md:p-16 rounded-3xl border border-gray-700/50 backdrop-blur-sm shadow-2xl relative z-10 transform transition-transform hover:scale-[1.01] duration-500">
        <div class="absolute -top-6 left-1/2 transform -translate-x-1/2">
            <div class="px-6 py-2 bg-gradient-to-r from-orange-500 to-red-500 rounded-full shadow-lg shadow-orange-500/20">
                <span class="text-white font-bold text-sm uppercase tracking-wider">Prueba gratuita de 14 días</span>
            </div>
        </div>
        
        <h2 class="text-4xl md:text-5xl font-extrabold mb-6 bg-gradient-to-r from-white to-gray-300 bg-clip-text text-transparent">Transforma tus reuniones hoy</h2>
        <p class="text-gray-400 text-lg mb-10 max-w-2xl mx-auto">Únete a miles de profesionales que ahorran tiempo y aumentan su productividad con Sumora.</p>
        
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="#signup" class="relative overflow-hidden group px-8 py-4 rounded-xl bg-gradient-to-r from-orange-500 to-red-500 text-white font-bold text-lg shadow-xl shadow-orange-500/20 transform transition-all duration-300 hover:-translate-y-1">
                <span class="relative z-10">Empieza gratis</span>
                <div class="absolute inset-0 bg-gradient-to-r from-orange-600 to-red-600 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
            </a>
            <a href="#demo" class="px-8 py-4 rounded-xl bg-white/10 backdrop-blur-sm border border-white/20 text-white font-bold text-lg transition-all duration-300 hover:bg-white/20">
                Ver demostración
            </a>
        </div>
        
        <div class="mt-10 flex flex-wrap justify-center gap-6 text-sm text-gray-400">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Sin tarjeta de crédito</span>
            </div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Cancelación en cualquier momento</span>
            </div>
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-400 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span>Soporte técnico 24/7</span>
            </div>
        </div>
    </div>
</section>

 <!-- FAQ -->
<section id="faq" class="py-32 px-6 bg-gradient-to-b from-gray-900 to-black relative overflow-hidden">
    <!-- Decorative elements -->
    <div class="absolute inset-0 bg-[radial-gradient(circle_at_30%_20%,rgba(249,115,22,0.15),transparent_40%),radial-gradient(circle_at_70%_60%,rgba(249,115,22,0.1),transparent_40%)]"></div>
    <div class="absolute -top-40 -right-40 w-96 h-96 bg-orange-500/5 rounded-full filter blur-3xl"></div>
    <div class="absolute -bottom-20 -left-20 w-80 h-80 bg-orange-600/5 rounded-full filter blur-3xl"></div>
    
    <div class="max-w-4xl mx-auto relative z-10">
        <div class="text-center mb-16">
            <div class="inline-block bg-orange-500/20 text-orange-300 px-3 py-1 rounded-full uppercase text-xs tracking-wider mb-4">Dudas</div>
            <h2 class="text-4xl md:text-5xl font-bold mb-6">Preguntas <span class="bg-gradient-to-r from-orange-400 to-red-500 bg-clip-text text-transparent">frecuentes</span></h2>
            <p class="text-gray-400 max-w-2xl mx-auto">Todo lo que necesitas saber sobre cómo Sumora puede transformar tus reuniones</p>
        </div>
        
        <div class="space-y-5">
            <!-- FAQ Item 1 -->
            <div class="group bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl overflow-hidden hover:border-orange-500/30 transition-all duration-300">
                <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none" onclick="
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.faq-icon');
                    content.classList.toggle('max-h-0');
                    content.classList.toggle('max-h-96');
                    content.classList.toggle('py-0');
                    content.classList.toggle('py-6');
                    icon.classList.toggle('rotate-45');
                ">
                    <span class="font-semibold text-xl text-white group-hover:text-orange-400 transition-colors">¿Cómo empiezo a usar Sumora?</span>
                    <span class="text-orange-500 h-6 w-6 flex items-center justify-center transition-transform duration-300 faq-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out border-t border-gray-700/50 px-6 py-0">
                    <p class="text-gray-300">
                        Es muy sencillo: regístrate en nuestra plataforma, sube tu primera grabación de reunión (audio o video) y en menos de 5 minutos recibirás un resumen detallado con todas las tareas extraídas y organizadas por prioridad. También puedes programar integraciones para automatizar todo el proceso.
                    </p>
                    <div class="mt-4 flex gap-2">
                        <a href="/register" class="text-orange-400 hover:text-orange-300 text-sm flex items-center">
                            <span>Crear cuenta</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L12.586 11H5a1 1 0 110-2h7.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </a>
                        <a href="#demo" class="text-gray-400 hover:text-gray-300 text-sm flex items-center">
                            <span>Ver demo</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 2 -->
            <div class="group bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl overflow-hidden hover:border-orange-500/30 transition-all duration-300">
                <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none" onclick="
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.faq-icon');
                    content.classList.toggle('max-h-0');
                    content.classList.toggle('max-h-96');
                    content.classList.toggle('py-0');
                    content.classList.toggle('py-6');
                    icon.classList.toggle('rotate-45');
                ">
                    <span class="font-semibold text-xl text-white group-hover:text-orange-400 transition-colors">¿Puedo cambiar de plan en cualquier momento?</span>
                    <span class="text-orange-500 h-6 w-6 flex items-center justify-center transition-transform duration-300 faq-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out border-t border-gray-700/50 px-6 py-0">
                    <p class="text-gray-300">
                        Absolutamente. Puedes actualizar o degradar tu suscripción en cualquier momento desde la sección de configuración de tu cuenta. Los cambios se aplicarán inmediatamente y los cargos se ajustarán de forma prorrateada. No hay compromisos a largo plazo ni penalizaciones por cambiar de plan.
                    </p>
                    <div class="mt-4 grid grid-cols-3 gap-4">
                        <div class="bg-gray-700/50 rounded-lg p-3 text-center">
                            <div class="text-orange-400 text-lg font-bold">Free</div>
                            <div class="text-gray-400 text-xs">1 reunión/mes</div>
                        </div>
                        <div class="bg-gradient-to-br from-orange-500/20 to-red-500/20 rounded-lg p-3 text-center">
                            <div class="text-orange-400 text-lg font-bold">Starter</div>
                            <div class="text-gray-400 text-xs">5 reuniones/mes</div>
                        </div>
                        <div class="bg-gray-700/50 rounded-lg p-3 text-center">
                            <div class="text-orange-400 text-lg font-bold">Pro</div>
                            <div class="text-gray-400 text-xs">20 reuniones/mes</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 3 -->
            <div class="group bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl overflow-hidden hover:border-orange-500/30 transition-all duration-300">
                <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none" onclick="
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.faq-icon');
                    content.classList.toggle('max-h-0');
                    content.classList.toggle('max-h-96');
                    content.classList.toggle('py-0');
                    content.classList.toggle('py-6');
                    icon.classList.toggle('rotate-45');
                ">
                    <span class="font-semibold text-xl text-white group-hover:text-orange-400 transition-colors">¿Dónde recibo los resúmenes?</span>
                    <span class="text-orange-500 h-6 w-6 flex items-center justify-center transition-transform duration-300 faq-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out border-t border-gray-700/50 px-6 py-0">
                    <p class="text-gray-300">
                        Sumora ofrece múltiples opciones para recibir tus resúmenes, según tus preferencias:
                    </p>
                    <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="flex items-center gap-2 bg-gray-700/30 rounded-lg p-3">
                            <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                            </svg>
                            <span class="text-gray-300 text-sm">Email</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-700/30 rounded-lg p-3">
                            <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-300 text-sm">Slack</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-700/30 rounded-lg p-3">
                            <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-300 text-sm">Notion</span>
                        </div>
                        <div class="flex items-center gap-2 bg-gray-700/30 rounded-lg p-3">
                            <svg class="h-5 w-5 text-orange-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5 4a3 3 0 00-3 3v6a3 3 0 003 3h10a3 3 0 003-3V7a3 3 0 00-3-3H5zm-1 9v-1h5v2H5a1 1 0 01-1-1zm7 1h4a1 1 0 001-1v-1h-5v2zm0-4h5V8h-5v2zM9 8H4v2h5V8z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-gray-300 text-sm">Sheets</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- FAQ Item 4 (New) -->
            <div class="group bg-gray-800/50 backdrop-blur-sm border border-gray-700/50 rounded-xl overflow-hidden hover:border-orange-500/30 transition-all duration-300">
                <button class="w-full flex justify-between items-center p-6 text-left focus:outline-none" onclick="
                    const content = this.nextElementSibling;
                    const icon = this.querySelector('.faq-icon');
                    content.classList.toggle('max-h-0');
                    content.classList.toggle('max-h-96');
                    content.classList.toggle('py-0');
                    content.classList.toggle('py-6');
                    icon.classList.toggle('rotate-45');
                ">
                    <span class="font-semibold text-xl text-white group-hover:text-orange-400 transition-colors">¿Qué idiomas soporta Sumora?</span>
                    <span class="text-orange-500 h-6 w-6 flex items-center justify-center transition-transform duration-300 faq-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                    </span>
                </button>
                <div class="max-h-0 overflow-hidden transition-all duration-500 ease-in-out border-t border-gray-700/50 px-6 py-0">
                    <p class="text-gray-300">
                        Actualmente Sumora soporta español, inglés, francés, alemán, italiano y portugués. Estamos trabajando continuamente para añadir más idiomas a nuestra plataforma.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="bg-gray-700/50 px-3 py-1 rounded-full text-xs text-gray-300">Español</span>
                        <span class="bg-gray-700/50 px-3 py-1 rounded-full text-xs text-gray-300">English</span>
                        <span class="bg-gray-700/50 px-3 py-1 rounded-full text-xs text-gray-300">Français</span>
                        <span class="bg-gray-700/50 px-3 py-1 rounded-full text-xs text-gray-300">Deutsch</span>
                        <span class="bg-gray-700/50 px-3 py-1 rounded-full text-xs text-gray-300">Italiano</span>
                        <span class="bg-gray-700/50 px-3 py-1 rounded-full text-xs text-gray-300">Português</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- More questions prompt -->
        <div class="mt-12 text-center">
            <p class="text-gray-400 mb-4">¿No encuentras lo que buscas?</p>
            <a href="#contact" class="inline-flex items-center px-6 py-3 bg-gray-800/70 hover:bg-gray-700/70 border border-gray-700/50 rounded-lg text-white transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-orange-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
                Contacta con nosotros
            </a>
        </div>
    </div>
</section>

  <!-- Footer -->
  <footer class="py-12 px-6 text-center text-gray-500 text-sm border-t border-gray-800">
    <p>&copy; 2025 Sumora.io — Todos los derechos reservados</p>
  </footer>
</body>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Remover clase active de todos los enlaces
            navLinks.forEach(navLink => {
                navLink.classList.remove('active');
            });
            
            // Agregar clase active al enlace clickeado
            this.classList.add('active');
        });
    });
});
</script>

</html>
