<div class="sidebar">

    <nav class="sidebar-nav">

        <ul class="nav">

            <li class="nav-title">
                Menu Opciones
            </li>

            <?php
                use App\Http\Controllers\AdmSessionController;
                $vent = AdmSessionController::listarPermisos();
            ?>

            @if ($vent['modulos'] == "baneado")

                <li>Cuenta sin acceso</li>

            @else

                @foreach ($vent['modulos'] as $item)

                    <li class="nav-item nav-dropdown menudown">

                        <a class="nav-link nav-dropdown-toggle" href="#">
                            <i
                                class="{{ $item->nombre_icono }}"
                                style="color:white;"
                            ></i>

                            <span style="color: turquoise; text-transform: capitalize;">
                                {{ $item->nombre }}
                            </span>
                        </a>

                        <ul class="nav-dropdown-items">

                            @foreach($item->ventanas as $ventana)

                                @if ($ventana->idmodulo == $item->id)

                                    <li
                                        @click="menu = {{ $ventana->codventana }}"
                                        class="nav-item"
                                        :style="menu == {{ $ventana->codventana }}
                                            ? {
                                                background: '#00c853',
                                                borderRadius: '5px',
                                                boxShadow: '0 0 8px #00ff66, 0 0 15px rgba(0,255,102,0.7)'
                                            }
                                            : {}"
                                    >
                                    

                                        <a
                                            class="nav-link"
                                            href="#"
                                            :style="menu == {{ $ventana->codventana }}
                                                ? {
                                                    color: 'white',
                                                    fontWeight: 'bold'
                                                }
                                                : {}"
                                        >

                                            <i
                                                class="fa fa-check"
                                                :style="menu == {{ $ventana->codventana }}
                                                    ? { color: 'white' }
                                                    : {}"
                                            ></i>

                                            {{ $ventana->nombre }}

                                        </a>

                                    </li>

                                    

                                @endif

                            @endforeach

                        </ul>

                    </li>

                @endforeach

            @endif

        </ul>

    </nav>

    <button
        class="sidebar-minimizer brand-minimizer"
        type="button">
    </button>

</div>