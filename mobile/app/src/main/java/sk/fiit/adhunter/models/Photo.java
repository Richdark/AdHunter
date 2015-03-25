package sk.fiit.adhunter.models;

import com.google.gson.annotations.SerializedName;

import java.io.Serializable;

/**
 * Created by jerry on 24. 11. 2014.
 */
public class Photo implements Serializable {

    /**
     * SerializedName annotation is maybe not necessary, as there are multiple parameters
     * being used in our web service, not Body annotation (see Retrofit documentation
     * and ServiceInterface.java)
     *
     * Body annotation also serializes data, which is not our case
     */

    @SerializedName("photo")
    private byte[] imageByteArray;

    @SerializedName("lat")
    private double latitude;

    @SerializedName("lng")
    private double longitude;

    @SerializedName("comment")
    private String comment = "";

    @SerializedName("backing_type")
    private String billboardType = "";

    @SerializedName("owner_id")
    private String owner = "";

    public byte[] getImageByteArray() {
        return imageByteArray;
    }

    public void setImageByteArray(byte[] imageByteArray) {
        this.imageByteArray = imageByteArray;
    }

    public double getLatitude() {
        return latitude;
    }

    public void setLatitude(double latitude) {
        this.latitude = latitude;
    }

    public double getLongitude() {
        return longitude;
    }

    public void setLongitude(double longitude) {
        this.longitude = longitude;
    }

    public String getComment() {
        return comment;
    }

    public void setComment(String comment) {
        this.comment = comment;
    }

    public String getBillboardType() {
        return billboardType;
    }

    public void setBillboardType(String billboardType) {
        this.billboardType = billboardType;
    }

    public String getOwner() {
        return owner;
    }

    public void setOwner(String owner) {
        this.owner = owner;
    }

}
