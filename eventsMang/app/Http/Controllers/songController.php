<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Carbon\Carbon;
use Defuse\Crypto\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
class songController extends Controller
{
public function index(){

$songs = Song::all();

// Check if there are any songs
if ($songs->isEmpty()) {
    // No songs found, return a view with a no songs message
    return $this->sendError('There are no songs yet');
} else {
    // Songs found, return the view with the list of songs
    return response()->json($songs ,200);
}

}

/////////////////
///create
/////////////////
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'song_category_id' => 'required|exists:song_categories,id',
            'song_name' => ['required', 'string', 'max:255'],
            'song' => 'required|file|mimes:mp3,wav|max:2048',
        ]);

        if (Auth::user()->role_name == 'super_admin') {

            if ($validator->fails()) {
                return $this->sendError('Validate your data', $validator->errors());
            }

            if ($request->has('song')) {
                $song = $request->song;
                $newAudio = time() . $song->getClientOriginalName();
                $song->move('song/storagee', $newAudio);
            }
            $songs = Song::create(
                [
                    'song_category_id' => $request->song_category_id,
                    'song_name' => $request->song_name,
                    'song' => 'song/storagee'.$newAudio
                ]

            );
            return response()->json([
                'message' => 'Audio uploaded successfully',
                'song' => $songs
            ], 200);
        } else {
            return $this->sendError('you don\'t have permission', '', 403);
        }
    }



///////////////
/////update song
///////////////


public function updateSong(Request $request, $id)
{
    $song = Song::find($id);
    if (!$song) {
        return $this->sendError('Song not found', '', 404);
    }

    $validator = Validator::make($request->all(), [
        'song_category_id' => 'required|exists:song_categories,id',
        'song_name' => ['required', 'string', 'max:255'],
        'song' => 'required|file|mimes:mp3,wav|max:2048',
    ]);

    if (Auth::user()->role_name == 'super_admin') {
        if ($validator->fails()) {
            return $this->sendError('Validate your data', $validator->errors());
        }

        // Initialize $newAudioPath with the existing song path or null
        $newAudioPath = $song->song; // Default to the existing path

        if ($request->hasFile('song')) {
            // If the old file path is not null and the file exists, delete it
            if (!is_null($song->song) && Storage::disk('public')->exists($song->song)) {
                Storage::disk('public')->delete($song->song);
            }

            // Handle the new file upload
            $uploadedSong = $request->file('song');
            $newAudio = time() . '_' . $uploadedSong->getClientOriginalName();
            $uploadedSong->move(public_path('song/update'), $newAudio);
            $newAudioPath = 'song/update/' . $newAudio;
        }

        // Update the song record
        $song->song_category_id = $request->song_category_id;
        $song->song_name = $request->song_name;
        $song->song = $newAudioPath; // Update with the new path or keep the existing one
        $song->save();

        return response()->json([
            'message' => 'Audio updated successfully',
            'song' => $song
        ], 200);
    } else {
        return $this->sendError('You don\'t have permission', '', 403);
    }
}


//////////////
//delete song
//////////////////
            public function deleteSong($id)
            {
                // Find the song by ID
                $song = Song::find($id);

                // Check if the song exists
                if (!$song) {

                    return response()->json([
                        'message' => 'Song not found'
                    ], 404);
                }

                try {
                    // Attempt to delete the song
                    $song->delete();

                    // Return a success response
                    return response()->json([
                        'message' => 'Song deleted successfully'
                    ], 200);
                } catch (\Exception $e) {
                    // If there's an error during the deletion, return a 500 response
                    return response()->json([
                        'message' => 'An error occurred while deleting the song'
                    ], 500);
                }
            }
        }















