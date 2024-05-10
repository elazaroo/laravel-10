<?php

namespace App\Http\Controllers;

use App\Models\Evolution;
use App\Models\Type;
use App\Models\Pokemons;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class pokemonController extends Controller
{
    public function getPokemons()
    {
        $pokemons = Pokemons::select('id', 'name', 'image')->get();
        if (!$pokemons->isEmpty()) {
            Log::info('[pokemonController] getPokemons() - Se ha accedido a la API de Pokemon y se han encontrado pokemons');
            return Response::json(array('code' => 200, 'status' => true, 'message' => "ok", 'data' => $pokemons), 200);
        } else {
            Log::error('[pokemonController] getPokemons() - Se ha accedido a la API de Pokemon y no se han encontrado pokemons');
            return Response::json(array('code' => 404, 'status' => false, 'message' => "No se encontraron pokemons"), 404);
        }
    }

    public function getPokemon($id)
    {
        $pokemon = Pokemons::where('id', $id)->select('id', 'name', 'image')->get();
        if (!$pokemon) {
            Log::error('[pokemonController] getPokemon() - Se ha accedido a la API de Pokemon y no se ha encontrado el pokemon con id ' . $id);
            return Response::json(array('code' => 404, 'status' => false, 'message' => "No se encontró ese pokemon"), 404);
        } else {
            Log::info('[pokemonController] getPokemon() - Se ha accedido a la API de Pokemon y se ha encontrado el pokemon con id ' . $id);
            return Response::json(array('code' => 200, 'status' => true, 'message' => "ok", 'data' => $pokemon), 200);
        }
    }

    public function getPokemonName(Request $request)
    {
        $name = $request->name;
        $pokemon = Pokemons::where('name', $name)->select('id', 'name', 'image')->get();
        if (!$pokemon) {
            Log::error('[pokemonController] getPokemonName() - Se ha accedido a la API de Pokemon y no se ha encontrado el pokemon con id ' . $name);
            return Response::json(array('code' => 404, 'status' => false, 'message' => "No se encontró ese pokemon"), 404);
        } else {
            Log::info('[pokemonController] getPokemonName() - Se ha accedido a la API de Pokemon y se ha encontrado el pokemon con id ' . $name);
            return Response::json(array('code' => 200, 'status' => true, 'message' => "ok", 'data' => $pokemon), 200);
        }
    }


    public function index()
    {
        return view('pokemon.index');
    }

    public function list($page)
    {
        if ($page < 1) {
            return Redirect::route('pokemon.list', ['page' => 1]);
        }
        if ($page > 16) {
            return Redirect::route('pokemon.list', ['page' => 16]);
        }
        $offset = ($page - 1) * 10;
        $pokemons = Pokemons::offset($offset)->limit(10)->get();
        $pokemonData = [];
        foreach ($pokemons as $pokemon) {
            $types = $pokemon->types()->pluck('name')->toArray();
            $pokemonData[] = [
                'id' => $pokemon->id,
                'name' => $pokemon->name,
                'image' => $pokemon->image,
                'types' => $types
            ];
        }
        $typesList = Type::all()->pluck('name')->toArray();
        return view('pokemon.list', ['pokemonData' => $pokemonData, 'page' => $page, 'typesList' => $typesList]);
    }

    public function show($id)
    {
        $pokemon = Pokemons::find($id);
        if ($pokemon == null) {
            return Redirect::route('pokemon.index');
        }
        $pokemonData = [
            'id' => $pokemon->id,
            'name' => $pokemon->name,
            'image' => $pokemon->image,
            'types' => $pokemon->types()->pluck('name')->toArray()
        ];
        $pokemonPostion = Evolution::where('pokemons_id', $id)->first()->position;
        $getFamily = Evolution::where('pokemons_id', $id)->first()->family;
        $invoIds = Evolution::where('family', $getFamily)
            ->where('position', '<', $pokemonPostion)
            ->pluck('pokemons_id')
            ->toArray();


        $evoIds = Evolution::where('family', $getFamily)
            ->where('position', '>', $pokemonPostion)
            ->pluck('pokemons_id')
            ->toArray();

        $invoData = Pokemons::whereIn('id', $invoIds)
            ->get(['id', 'name', 'image'])
            ->map(function ($pokemon) {
                $pokemon->types = $pokemon->types()->pluck('name')->toArray();
                return $pokemon;
            })
            ->toArray();

        $evoData = Pokemons::whereIn('id', $evoIds)
            ->get(['id', 'name', 'image'])
            ->map(function ($pokemon) {
                $pokemon->types = $pokemon->types()->pluck('name')->toArray();
                return $pokemon;
            })
            ->toArray();

        return view('pokemon.show', ['pokemonData' => $pokemonData, 'invoData' => $invoData, 'evoData' => $evoData]);
    }

    public function listType($type, $page)
    {
        if ($page < 1) {
            return Redirect::route('pokemon.type', ['type' => $type, 'page' => 1]);
        }
        $maxPage = ceil(Type::where('name', $type)->first()->pokemons()->count() / 10);
        if ($page > $maxPage) {
            return Redirect::route('pokemon.type', ['type' => $type, 'page' => $maxPage]);
        }

        $offset = ($page - 1) * 10;
        $type = Type::where('name', $type)->first();
        $pokemonIds = $type->pokemons()->pluck('pokemons.id');
        $pokemonData = Pokemons::whereIn('pokemons.id', $pokemonIds)
            ->offset($offset)
            ->limit(10)
            ->get(['pokemons.id', 'name', 'image'])
            ->toArray();

        $typesList = Type::all()->pluck('name')->toArray();

        return view('pokemon.list', ['pokemonData' => $pokemonData, 'selectedType' => $type->name, 'typesList' => $typesList, 'page' => $page]);
    }

    public function apiCaller()
    {
        if (Pokemons::query()->count() === 0 || Type::query()->count() === 0) {
            Log::info('[pokemonController] apicaller() - Alguna de las tablas está vacias, llamando a API...');
            $this->callApi();
        } else {
            Log::info('[pokemonController] apicaller() - Tablas llenas, no se llamará a API');
        }

        // return redirect()->route('pokemon.index');
        return Response::json(array('code' => 200, 'status' => true, 'message' => "ok"), 200);
    }

    public function callApi(): void
    {
        $pokemonInfo = array();
        $insertedPokemons = array();
        $insertedRelations = array();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://pokeapi.co/api/v2/pokemon?limit=151');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Desactivar la verificación del certificado SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error('Error en la solicitud: ' . curl_error($curl));
        } else {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($http_code == 200) {
                $data = json_decode($output);
                Log::info('[pokemonController] callApi() - Se ha accedido a la API de Pokemon');

                // Recorrer los resultados y obtener los detalles de cada Pokemons
                foreach ($data->results as $pokemon) {
                    $pokemon_curl = curl_init();
                    curl_setopt($pokemon_curl, CURLOPT_URL, $pokemon->url);
                    curl_setopt($pokemon_curl, CURLOPT_RETURNTRANSFER, 1);

                    // Desactivar la verificación del certificado SSL
                    curl_setopt($pokemon_curl, CURLOPT_SSL_VERIFYHOST, 0);
                    curl_setopt($pokemon_curl, CURLOPT_SSL_VERIFYPEER, 0);

                    $pokemon_output = curl_exec($pokemon_curl);
                    if (curl_errno($pokemon_curl)) {
                        Log::error('Error en la solicitud: ' . curl_error($pokemon_curl));
                    } else {
                        $pokemon_data = json_decode($pokemon_output);

                        //Sacar los nombres de los tipos (puede haber mas de uno)
                        $types = array_map(function ($type) {
                            return $type->type->name;
                        }, $pokemon_data->types);
                        $sprites = $pokemon_data->sprites->front_default;

                        $pokemonInfo[] = [
                            'name' => $pokemon_data->name,
                            'types' => $types,
                            'image' => $sprites,
                        ];
                    }
                    curl_close($pokemon_curl);
                }
                Log::info('[pokemonController] callApi() - Se han obtenido los detalles de los pokemons ' . implode(', ', array_column($pokemonInfo, 'name')));
            } else {
                Log::error('[pokemonController] callApi() - La API devolvió un código HTTP: ' . $http_code);
            }
        }

        curl_close($curl);

        //Recorrer el array $pokemonInfo y comprobar si ese pokemon ya esta metido, si no lo esta, lo añadirá a la bbdd
        foreach ($pokemonInfo as $pokemon) {
            $pokemonExists = Pokemons::where('name', $pokemon['name'])->first();
            if (!$pokemonExists) {
                Pokemons::create([
                    'name' => $pokemon['name'],
                    'image' => $pokemon['image'],
                ]);
                // array el cual almacena todos los nombres de los pokemons instertados
                $insertedPokemons[] = $pokemon['name'];
            }
        }
        if ($insertedPokemons != null) {
            Log::info('[pokemonController] callApi() - Se han insertado (SQL) los siguientes pokemons: ' . implode(', ', $insertedPokemons));
        } else {
            Log::info('[pokemonController] callApi() - No se han insertado (SQL) pokemons nuevos');
        }

        if ($this->callApiTypes() == false) {
            Log::error('[pokemonController] callApi - Error al acceder a la API de tipos de Pokemon');
        }

        // relacionar pokemons con sus tipos a traves de $pokemonInfo
        foreach ($pokemonInfo as $pokemon) {
            $pokemonModel = Pokemons::where('name', $pokemon['name'])->first();
            $types = Type::whereIn('name', $pokemon['types'])->get();

            foreach ($types as $type) {
                if (!$pokemonModel->types->contains($type)) {
                    $pokemonModel->types()->attach($type->id);
                    $insertedRelations[] = $pokemonModel->name . ' - ' . $type->name;
                }
            }
        }
        if ($insertedRelations != null) {
            Log::info('[pokemonController] callApi() - Se han insertado (SQL) las siguientes relaciones: ' . implode(', ', $insertedRelations));
        } else {
            Log::info('[pokemonController] callApi() - No se han insertado (SQL) relaciones nuevas');
        }

        if (!$this->callApiEvolutions()) {
            Log::alert('[pokemonController] callApi() - Error al acceder a todas APIs de evoluciones de Pokemon');
        } else {
        }
    }

    public function callApiTypes()
    {
        $insertedTypes = array();

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, 'https://pokeapi.co/api/v2/type');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        // Desactivar la verificación del certificado SSL
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($curl);

        if (curl_errno($curl)) {
            Log::error('Error en la solicitud: ' . curl_error($curl));
        } else {
            $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
            if ($http_code == 200) {
                $data = json_decode($output);
                Log::info('[pokemonController] callApiTypes() - Se ha accedido a la API de tipos de Pokemon');
                Log::info('[pokemonController] callApiTypes() - Se han obtenido los tipos de Pokemon: ' . implode(', ', array_column($data->results, 'name')));

                // Recorrer los resultados y obtener los nombres de cada tipo
                foreach ($data->results as $type) {
                    $typeExists = Type::where('name', $type->name)->first();
                    if (!$typeExists) {
                        Type::create([
                            'name' => $type->name,
                        ]);
                        $insertedTypes[] = $type->name;
                    }
                }
                if ($insertedTypes != null) {
                    Log::info('[pokemonController] callApiTypes() - Se han insertado (SQL) los siguientes tipos: ' . implode(', ', $insertedTypes));
                } else {
                    Log::info('[pokemonController] callApiTypes() - No se han insertado (SQL) tipos nuevos');
                }
            } else {
                Log::error('[pokemonController] callApiTypes() - La API devolvió un código HTTP: ' . $http_code);
                return false;
            }
        }
        return true;
    }

    public function callApiEvolutions()
    {
        $contOkEvo = 0;
        $contFailEvo = 0;

        for ($cont = 1; $cont < 79; $cont++) {
            $evolutions = 'https://pokeapi.co/api/v2/evolution-chain/' . $cont;
            $evolutions_curl = curl_init();

            curl_setopt($evolutions_curl, CURLOPT_URL, $evolutions);
            curl_setopt($evolutions_curl, CURLOPT_RETURNTRANSFER, 1);

            // Desactivar la verificación del certificado SSL
            curl_setopt($evolutions_curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($evolutions_curl, CURLOPT_SSL_VERIFYPEER, 0);

            $evolutions_output = curl_exec($evolutions_curl);

            if (curl_errno($evolutions_curl)) {
                Log::error('Error en la solicitud: ' . curl_error($evolutions_curl));
            } else {
                $http_code = curl_getinfo($evolutions_curl, CURLINFO_HTTP_CODE);
                if ($http_code == 200) {
                    $evolutionsProcessed = json_decode($evolutions_output);
                    $evolutionsProcessed = $evolutionsProcessed->chain;
                    $contOkEvo++;
                    $insertedEvolutions = $this->recursiveEvolutions($evolutionsProcessed, $cont, 0);
                } else {
                    $contFailEvo++;
                }
            }
        }
        if ($contFailEvo == 0) {
            Log::info('[pokemonController] callApiEvolutions() - Se han obtenido las evoluciones de todos los pokemons ' . $contOkEvo);
            if ($insertedEvolutions != null) {
                Log::info('[pokemonController] recursiveEvolutions() - Se han insertado (SQL) las siguientes evoluciones: ' . $insertedEvolutions);
            } else {
                Log::info('[pokemonController] callApiEvolutions() - No se han insertado (SQL) evoluciones nuevas');
            }
            return true;
        } else {
            Log::error('[pokemonController] callApiEvolutions() - No se han obtenido las evoluciones de todos los pokemons ' . $contFailEvo . ' falladas y ' . $contOkEvo . ' correctas');
            if ($insertedEvolutions != null) {
                Log::info('[pokemonController] recursiveEvolutions() - Se han insertado (SQL) evoluciones');
            } else {
                Log::info('[pokemonController] callApiEvolutions() - No se han insertado (SQL) evoluciones nuevas');
            }
            return false;
        }
    }

    /* RECURSIVA SIN EEVEE
    
    public function recursiveEvolutions($evolutions, $family, $insertedEvolutions)
    {
        $nextEvolvesTo = !empty($evolutions?->evolves_to) ? $evolutions?->evolves_to[0] : null;

        $name = $evolutions?->species?->name;
        $nextName = $nextEvolvesTo?->species?->name;

        $idPokemon = Pokemons::where('name', $name)->first()?->id;
        if ($idPokemon) {
            if (Evolution::where('pokemons_id', $idPokemon)->first()) {
                return;
            }

            $getPosition = Evolution::where('family', $family)->orderBy('position', 'DESC')?->first()?->position;
            if ($getPosition == null) {
                $getPosition = 0;
            }
            $newEvolution = new Evolution();
            $newEvolution->family = $family;
            $newEvolution->pokemons_id = $idPokemon;
            $newEvolution->position = $getPosition + 1;
            $newEvolution->save();
            $insertedEvolutions++;
        }
        if ($nextName) {
            $this->recursiveEvolutions($nextEvolvesTo, $family, $insertedEvolutions);
        }
        return $insertedEvolutions;
    } */

    public function recursiveEvolutions($evolutions, $family, $insertedEvolutions)
    {
        $name = $evolutions?->species?->name;
        $idPokemon = Pokemons::where('name', $name)->first()?->id;
        if ($idPokemon) {
            if (Evolution::where('pokemons_id', $idPokemon)->first()) {
                return;
            }

            $getPosition = Evolution::where('family', $family)->orderBy('position', 'DESC')?->first()?->position;
            if ($getPosition == null) {
                $getPosition = 0;
            }
            $newEvolution = new Evolution();
            $newEvolution->family = $family;
            $newEvolution->pokemons_id = $idPokemon;
            $newEvolution->position = $getPosition + 1;
            $newEvolution->save();
            $insertedEvolutions++;
        }

        if (!empty($evolutions?->evolves_to)) {
            foreach ($evolutions->evolves_to as $nextEvolvesTo) {
                $insertedEvolutions = $this->recursiveEvolutions($nextEvolvesTo, $family, $insertedEvolutions);
            }
        }

        return $insertedEvolutions;
    }
}
