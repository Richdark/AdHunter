package sk.fiit.adhunter.services.io;

import com.google.gson.annotations.SerializedName;

/**
 * Created by jerry on 12/05/15.
 */
public class GetLoginResponse {

    @SerializedName("status")
    public String status;

    @SerializedName("message")
    public String message;

}
