<?php

namespace App\Http\Controllers;

use App\Models\User;
use Corcel\Model\Post;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    /**
     * @OA\Get(
     *    path="/api/emploi",
     *   tags={"emploi"},
     *  summary="Get all emploi",
     * description="Get all emploi",
     *  @OA\Response(
     *        response=200,
     *       description="successful operation"
     *  ),
     * @OA\Response(
     *        response=400,
     *      description="Bad Request"
     * ),
     * @OA\Response(
     *       response=404,
     *     description="Resource Not Found"
     * ),
     * @OA\Response(
     *       response=500,
     *    description="Internal Server Error"
     * ),
     * )
     */
    public function  getLisEmploi()
    {
        $whereClose = ['post_type' => 'job_listing', 'post_status' => 'publish'];
        $post = Post::join('postmeta', 'posts.ID', '=', 'postmeta.post_id')
            ->where($whereClose)
            ->where('postmeta.meta_key', '_job_location')
            ->get();
        return $post->map(function ($data) {
            return [
                'id' => $data->ID,
                'name' => $data->title,
                'content' => $data->content,
                'meta' => $data->meta->map(function ($meta) {
                    return [
                        'key' => $meta->meta_key,
                        'value' => $meta->value
                    ];
                })
            ];
        });
    }

    /**
     * @OA\Get(
     *   path="/api/emploi/{id}",
     *  tags={"emploi"},
     * summary="Get emploi by id",
     * description="Get emploi by id",
     * @OA\Parameter(
     *    name="id",
     *   in="path",
     *  description="emploi id",
     * required=true,
     * @OA\Schema(
     *             type="integer",
     *        format="int64"
     *    )
     * ),
     * @OA\Response(
     *       response=200,
     *    description="successful operation"
     * ),
     * @OA\Response(
     *       response=400,
     *    description="Bad Request"
     * ),
     * @OA\Response(
     *      response=404,
     *   description="Resource Not Found"
     * ),
     * )
     */
    public function getEmploi($id)
    {
        $whereClose = ['post_type' => 'job_listing', 'post_status' => 'publish'];
        $post = Post::join('postmeta', 'posts.ID', '=', 'postmeta.post_id')
            ->where($whereClose)
            ->where('postmeta.meta_key', '_job_location')
            ->where('posts.ID', $id)
            ->get();
        return $post->map(function ($data) {
            return [
                'id' => $data->ID,
                'name' => $data->title,
                'content' => $data->content,
                'meta' => $data->meta->map(function ($meta) {
                    return [
                        'key' => $meta->meta_key,
                        'value' => $meta->value
                    ];
                })
            ];
        });
    }

    /**
     * @OA\Get(
     *  path="/api/cv",
     * tags={"cv"},
     * summary="Get all cv",
     * description="Get all cv",
     * @OA\Response(
     *       response=200,
     *   description="successful operation"
     * ),
     * @OA\Response(
     *      response=400,
     *  description="Bad Request"
     * ),
     * @OA\Response(
     *    response=404,
     * description="Resource Not Found"
     * ),
     * )
     */
    public function getAllPostCv()
    {
        $whereClose = ['post_type' => 'resume', 'post_status' => 'publish'];
        $cv = Post::join('postmeta', 'posts.ID', '=', 'postmeta.post_id')
            ->where($whereClose)
            ->where('postmeta.meta_key', '_candidate_email')
            ->get();
        return $cv->map(function ($data) {
            return [
                'id' => $data->ID,
                'name' => $data->title,
                'content' => $data->content,
                'meta' => $data->meta->map(function ($meta) {
                    return [
                        'key' => $meta->meta_key,
                        'value' => $meta->value
                    ];
                })
            ];
        });
    }

    /**
     * @OA\Get(
     * path="/api/cv/{id}",
     * tags={"cv"},
     * summary="Get cv by id",
     * description="Get cv by id",
     * @OA\Parameter(
     *   name="id",
     * in="path",
     * description="cv id",
     * required=true,
     * @OA\Schema(
     *            type="integer",
     *      format="int64"
     * )
     * ),
     * @OA\Response(
     *      response=200,
     *  description="successful operation"
     * ),
     * @OA\Response(
     *     response=400,
     * description="Bad Request"
     * ),
     * @OA\Response(
     *   response=404,
     * description="Resource Not Found"
     * ),
     * )
     */
    public function getPostCv($id)
    {
        $whereClose = ['post_type' => 'resume', 'post_status' => 'publish', 'ID' => $id];
        $cv = Post::join('postmeta', 'posts.ID', '=', 'postmeta.post_id')
            ->where($whereClose)
            ->where('postmeta.meta_key', '_candidate_email')
            ->get();
        return $cv->map(function ($data) {
            return [
                'id' => $data->ID,
                'name' => $data->title,
                'content' => $data->content,
                'meta' => $data->meta->map(function ($meta) {
                    return [
                        'key' => $meta->meta_key,
                        'value' => $meta->value
                    ];
                })
            ];
        });
    }


    //function pour creer un nouvelle utilisateur

    /**
     * @OA\Post(
     * path="/api/user",
     * tags={"user"},
     * summary="Create new user",
     * description="Create new user",
     * @OA\RequestBody(
     *    required=true,
     *    @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *          @OA\Property(
     *             property="name",
     *             type="string",
     *             description="user name"
     *          ),
     *          @OA\Property(
     *             property="email",
     *             type="string",
     *             description="user email"
     *          ),
     *          @OA\Property(
     *             property="password",
     *             type="string",
     *             description="user password"
     *          ),
     *          @OA\Property(
     *             property="meta",
     *             type="array",
     *             description="user meta",
     *             @OA\Items(
     *                @OA\Property(
     *                   property="key",
     *                   type="string",
     *                   description="user meta key"
     *                ),
     *                @OA\Property(
     *                   property="value",
     *                   type="string",
     *                   description="user meta value"
     *                )
     *             )
     *          )
     *       )
     *    )
     * ),
     * @OA\Response(
     *      response=200,
     *  description="successful operation"
     * ),
     * @OA\Response(
     *     response=400,
     * description="Bad Request"
     * ),
     * @OA\Response(
     *   response=404,
     * description="Resource Not Found"
     * ),
     * )
     */
    /* public function createUser(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->save();
        $user->meta()->createMany($request->meta);
        return $user;
    }*/

    //fonction pour recuperer les utilisateurs

    /**
     * @OA\Get(
     * path="/api/users",
     * tags={"users"},
     * summary="Get all users",
     * description="Get all users",
     * @OA\Response(
     *      response=200,
     *  description="successful operation"
     * ),
     * @OA\Response(
     *     response=400,
     * description="Bad Request"
     * ),
     * @OA\Response(
     *   response=404,
     * description="Resource Not Found"
     * ),
     * )
     */

    public function getUsers()
    {
        $users = User::all();
        return $users->map(function ($data) {
            return [
                'id' => $data->id,
                'name' => $data->name,
                'email' => $data->email,
                'meta' => $data->meta->map(function ($meta) {
                    return [
                        'key' => $meta->key,
                        'value' => $meta->value
                    ];
                })
            ];
        });
    }

    //fonction login

    /**
     * @OA\Post(
     * path="/api/login",
     * tags={"login"},
     * summary="Login",
     * description="Login",
     * @OA\RequestBody(
     *    required=true,
     *    @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *          @OA\Property(
     *             property="email",
     *             type="string",
     *             description="user email"
     *          ),
     *          @OA\Property(
     *             property="password",
     *             type="string",
     *             description="user password"
     *          )
     *       )
     *    )
     * ),
     * @OA\Response(
     *      response=200,
     *  description="successful operation"
     * ),
     * @OA\Response(
     *     response=400,
     * description="Bad Request"
     * ),
     * @OA\Response(
     *   response=404,
     * description="Resource Not Found"
     * ),
     * )
     */

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('MyApp')->accessToken;
                return response()->json(['token' => $token], 200);
            } else {
                return response()->json(['error' => 'Unauthorised'], 401);
            }
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }

    //fonction logout

    /**
     * @OA\Post(
     * path="/api/logout",
     * tags={"logout"},
     * summary="Logout",
     * description="Logout",
     * @OA\RequestBody(
     *    required=true,
     *    @OA\MediaType(
     *       mediaType="application/json",
     *       @OA\Schema(
     *          @OA\Property(
     *             property="token",
     *             type="string",
     *             description="user token"
     *          )
     *       )
     *    )
     * ),
     * @OA\Response(
     *      response=200,
     *  description="successful operation"
     * ),
     * @OA\Response(
     *     response=400,
     * description="Bad Request"
     * ),
     * @OA\Response(
     *   response=404,
     * description="Resource Not Found"
     * ),
     * )
     * 
     */

    public function logout(Request $request)
    {
        $user = User::where('api_token', $request->token)->first();
        if ($user) {
            $user->api_token = null;
            $user->save();
            return response()->json(['message' => 'Logout successfully'], 200);
        } else {
            return response()->json(['error' => 'Unauthorised'], 401);
        }
    }
}
