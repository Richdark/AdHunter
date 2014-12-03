package com.vcelicky.smog.models;

import java.io.Serializable;

/**
 * Created by jerry on 24. 11. 2014.
 */
public class Photo implements Serializable {

    private String path;
    private byte[] imageByteArray;
    private double latitude;
    private double longitude;

    public String getPath() {
        return path;
    }

    public void setPath(String path) {
        this.path = path;
    }

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
}
