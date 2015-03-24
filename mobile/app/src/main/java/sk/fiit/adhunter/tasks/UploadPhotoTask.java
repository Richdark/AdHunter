package sk.fiit.adhunter.tasks;

import android.app.ProgressDialog;
import android.content.Context;
import android.os.AsyncTask;
import android.util.Log;
import android.widget.Toast;

import sk.fiit.adhunter.AsyncTaskCompleteListener;
import sk.fiit.adhunter.models.Photo;

import java.io.BufferedInputStream;
import java.io.BufferedReader;
import java.io.DataOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.MalformedURLException;
import java.net.URL;

/**
 * Created by jerry on 24. 11. 2014.
 */
public class UploadPhotoTask extends AsyncTask<Photo, Integer, String> {
    private static final String TAG = "UploadPhotoTask";
    private static final String URL = "http://team14-14.ucebne.fiit.stuba.sk/adhunter/billboards/add";

    private Context mContext;
    private Photo mPhoto;
    private AsyncTaskCompleteListener mListener;
    private String response = "NO RESPONSE";
    private ProgressDialog progressDialog;
    private int sizeOfPhotoList;

    public UploadPhotoTask(Context context, AsyncTaskCompleteListener listener) {
        mContext = context;
        mListener = listener;
    }

    @Override
    protected void onPreExecute() {
        super.onPreExecute();
        progressDialog = new ProgressDialog(mContext);
        progressDialog.setTitle("Upload fotiek");
        progressDialog.setMessage("Vaše fotky sa uploadujú...");
        progressDialog.setProgressStyle(ProgressDialog.STYLE_SPINNER);
        progressDialog.show();
    }

    @Override
    protected String doInBackground(Photo... photos) {
        mPhoto = photos[0];

        try {
            Log.d(TAG, "doInBackground started!");
            HttpURLConnection conn = null;
            URL url = new URL(URL);
            String attachmentName = "photo";
            String attachmentFileName = "photo.jpg";
            String boundary =  "*****";
            String twoHyphens = "--";
            String crlf = "\r\n";

            for(int i = 0; i < photos.length; i++) { //i < photos.length
                Log.d(TAG, "i = " + i);

                conn = (HttpURLConnection) url.openConnection();
                conn.setDoInput(true);
                conn.setDoOutput(true);
                conn.setUseCaches(false);
                conn.setRequestMethod("POST");
                conn.setRequestProperty("Connection", "Keep-Alive");
                conn.setRequestProperty("Content-Type", "multipart/form-data;boundary=" + boundary);

                DataOutputStream request = new DataOutputStream(conn.getOutputStream());

                request.writeBytes(twoHyphens + boundary + crlf);
                request.writeBytes("Content-Disposition: form-data; name=\"" + attachmentName
                        + "\";filename=\"" + attachmentFileName + "\"" + crlf);
                request.writeBytes(crlf);

                if(photos[i].getImageByteArray() == null) {
                    Log.d(TAG, "imageByteArray == null");
                } else {
                    request.write(photos[i].getImageByteArray());
                }

                request.writeBytes(crlf);
                //request.writeBytes(twoHyphens + boundary + twoHyphens + crlf);

                request.writeBytes(twoHyphens + boundary + crlf);
                request.writeBytes("Content-Disposition: form-data; name=\"lat\"" + crlf);
                request.writeBytes(crlf);
                request.writeBytes(String.valueOf(photos[i].getLatitude()));
                request.writeBytes(crlf);

                request.writeBytes(twoHyphens + boundary + crlf);
                request.writeBytes("Content-Disposition: form-data; name=\"lng\"" + crlf);
                request.writeBytes(crlf);
                request.writeBytes(String.valueOf(photos[i].getLongitude()));
                request.writeBytes(crlf);

                request.writeBytes(twoHyphens + boundary + crlf);
                request.writeBytes("Content-Disposition: form-data; name=\"backing_type\"" + crlf);
                request.writeBytes(crlf);
                Log.d(TAG, "typ billboardu = " + photos[i].getBillboardType());
                request.writeBytes(String.valueOf(photos[i].getBillboardType()));
                request.writeBytes(crlf);

                request.writeBytes(twoHyphens + boundary + crlf);
                request.writeBytes("Content-Disposition: form-data; name=\"owner_id\"" + crlf);
                request.writeBytes(crlf);
                request.writeBytes(String.valueOf(photos[i].getOwner()));
                request.writeBytes(crlf);

                // sending a comment
                request.writeBytes(twoHyphens + boundary + crlf);
                request.writeBytes("Content-Disposition: form-data; name=\"comment\"" + crlf);
                request.writeBytes(crlf);
                request.writeBytes(String.valueOf(photos[i].getComment()));
                Log.d(TAG, "comment = " + photos[i].getComment());
                request.writeBytes(crlf);
                request.writeBytes(twoHyphens + boundary + twoHyphens + crlf);

                request.flush();
                request.close();

                InputStream responseStream = new BufferedInputStream(conn.getInputStream());
                BufferedReader responseStreamReader = new BufferedReader(new InputStreamReader(responseStream));
                String line = "";
                StringBuilder stringBuilder = new StringBuilder();
                while ((line = responseStreamReader.readLine()) != null) {
                    stringBuilder.append(line).append("\n");
                }
                responseStreamReader.close();
                response = stringBuilder.toString();
                responseStream.close();

                conn.disconnect();
            }

            sizeOfPhotoList = photos.length;

        } catch (MalformedURLException e) {
            e.printStackTrace();
        } catch (IOException e) {
            e.printStackTrace();
        }
        return null;
    }

    @Override
    protected void onPostExecute(String s) {
        super.onPostExecute(s);
        progressDialog.dismiss();
        Toast.makeText(mContext, "Number of photos sent = " + String.valueOf(sizeOfPhotoList) + "\n" + response, Toast.LENGTH_SHORT).show();
        mListener.onTaskComplete();
    }
}
