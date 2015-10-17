package sk.fiit.adhunter.models;

import com.google.gson.annotations.SerializedName;

/**
 * Created by jerry on 10/03/15.
 */
public class Owner {

    public Owner() {}

    public Owner(String id, String name) {
        this.id = id;
        this.name = name;
    }

    @SerializedName("id")
    public String id;

    @SerializedName("name")
    public String name;

}
