<?php

namespace App\Http\Controllers;

use App\Http\Requests\RelationDeleteRequest;
use App\Http\Requests\RelationListRequest;
use App\Http\Requests\RelationRandomRequest;
use App\Http\Requests\RelationStoreRequest;
use App\Models\Relation;
use App\Models\User;

const FIRST_GRADE_DEPTH = 'direct';
const SECOND_GRADE_DEPTH = 'indirect';

class RelationsController extends Controller
{
 
	/**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\UserRegisterRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RelationStoreRequest $request)
    {
		$user = User::where('email', $request->input('email'))->first();
		$related = User::where('email', $request->input('related_email'))->first();
		
		if($this->relation_taken($user, $related)) {
			return json_error([], 'Relation already exists');
		}

		$relation = new Relation();
		$relation->user_id = $user->id;
		$relation->related_id = $related->id;
		$relation->save();

		return json_success(['message' => 'The user '. $related->email .' has been added to your network']);
    }


	/**
     * Delete a new relationship.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(RelationDeleteRequest $request)
    {
		$user = User::where('email', $request->input('email'))->first();
		$related = User::where('email', $request->input('related_email'))->first();
		
		$relation = Relation::where('user_id', $user->id)->where('related_id', $related->id)->first();
		
		if(!$relation) {
			return json_error([], 'Relation does not exists');
		}

		$relation->delete();

		return json_success(['message' => 'The user '. $related->email .' has been removed to your network']);
    }

	/**
     * Get all relations in my network and in my friends network
     *
     * 
     */
    public function index(RelationListRequest $request, $depth)
    {
		$user = User::where('email', $request->input('email'))->first();
		$array_direct = [];
		$array_indirect = [];
		foreach($user->relations as $relation) {
			$relation_object = $this->get_related($relation);
			array_push($array_direct, $relation_object);

			if ($depth == SECOND_GRADE_DEPTH) {
				foreach ($relation->relatedUser->relations as $indirect_relation) {
					if(!$this->relation_taken($user, $indirect_relation->relatedUser) && $user->email != $indirect_relation->relatedUser->email) {
						array_push($array_indirect, $this->get_related($indirect_relation));
					}
				}
			}
		}
		
		$data = ["direct" => $array_direct];

		if($depth == SECOND_GRADE_DEPTH) {
			$data = ["indirect" => $array_indirect];
		}

		return json_success($data);
    }

	public function random(RelationRandomRequest $request) {
		$user = User::where('email', $request->input('email'))->first();
		

		$random_users = User::inRandomOrder()->get()->take($request->input('random'));
		
		foreach ($random_users as $random) {
			if(!$this->relation_taken($user, $random)) {
				$relation = new Relation();
				$relation->user_id = $user->id;
				$relation->related_id = $random->id;
				$relation->save();
			}
		}
	}

	protected function relation_taken($user, $related) {
			return Relation::where('user_id', $user->id)->where('related_id', $related->id)->count();
	}

	protected function get_related(Relation $relation) {
		$related = User::where('id', $relation->related_id)->first();
			return [
				'email'			=> $related->email,
				'first_name' 	=> $related->first_name,
				'last_name'		=> $related->last_name,
				'country' 		=> $related->country,
				'badge'	 		=> $this->getBadge($related),
			];
	}

	protected function getBadge(User $user) {
		$count = Relation::where('user_id', $user->id)->get();
		$count = $count->count();
		switch (true) {
			case $count <= 10:
				$badge = "Nothing";
				break;
			case $count <= 44:				
				$badge = "Bronze";
				break;
			case $count <= 99:
				$badge = "Silver";
				break;
			case $count <= 144:
					$badge = "Gold";
					break;	
			case $count >= 145:
				$badge = "Platinum";
				break;
			default:
				$badge = "Nothing";
			break;
		}
		
		return $badge;
	}
}

