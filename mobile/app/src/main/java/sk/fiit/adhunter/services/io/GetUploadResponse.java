package sk.fiit.adhunter.services.io;

import com.google.gson.annotations.SerializedName;

/**
 * Created by jerry on 03/04/15.
 */
public class GetUploadResponse {

    @SerializedName("status")
    public String status;

    @SerializedName("message")
    public String message;

}
