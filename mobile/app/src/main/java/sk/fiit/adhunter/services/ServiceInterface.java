package sk.fiit.adhunter.services;

import java.util.List;

import retrofit.Callback;
import retrofit.client.Response;
import retrofit.http.Field;
import retrofit.http.FormUrlEncoded;
import retrofit.http.GET;
import retrofit.http.Multipart;
import retrofit.http.POST;
import retrofit.http.Part;
import retrofit.mime.TypedByteArray;
import retrofit.mime.TypedFile;
import retrofit.mime.TypedString;
import sk.fiit.adhunter.models.Owner;
import sk.fiit.adhunter.services.io.GetUploadResponse;

/**
 * Created by jerry on 17. 2. 2015.
 */
public interface ServiceInterface {

    @FormUrlEncoded
    @POST("/auth/login/")
    void loginUser(@Field("email") String email, @Field("password") String password, @Field("uid") String uid, @Field("send") String send, Callback<Response> response);

    @FormUrlEncoded
    @POST("/auth/logout_user/")
    void logoutUser(@Field("uid") String uid, Callback<Response> response);

    @Multipart
    @POST("/billboards/add")
    void uploadPhoto(@Part("photo\"; filename=\"photo.jpg") TypedByteArray photo,
                     @Part("lat") TypedString latitude,
                     @Part("lng") TypedString longitude,
                     @Part("comment") TypedString comment,
                     @Part("backing_type") TypedString billboardType,
                     @Part("owner_id") TypedString owner,
                     Callback<GetUploadResponse> response);

    @GET("/owners/current_list/")
    void getOwnersList(Callback<List<Owner>> response);

}
