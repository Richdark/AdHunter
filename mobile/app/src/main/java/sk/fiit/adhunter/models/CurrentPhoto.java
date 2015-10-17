package sk.fiit.adhunter.models;

/**
 * Created by jerry on 4. 3. 2015.
 */
public class CurrentPhoto extends Photo {

    private static CurrentPhoto instance = null;
    private CurrentPhoto() {}

    public static CurrentPhoto getInstance() {
        if(instance == null) {
            instance = new CurrentPhoto();
        }
        return instance;
    }

    public void clearInstance() {
        instance = null;
    }

}
