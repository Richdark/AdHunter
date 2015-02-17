package com.vcelicky.smog.services;

import retrofit.Callback;
import retrofit.client.Response;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.POST;

/**
 * Created by jerry on 17. 2. 2015.
 */
public interface ServiceInterface {

    @FormUrlEncoded
    @POST("/auth/login_user/")
    void loginUser(@Field("email") String email, @Field("password") String password, @Field("uid") String uid, Callback<Response> response);

}
