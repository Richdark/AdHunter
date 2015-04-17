package sk.fiit.adhunter.views;

import android.content.Context;
import android.hardware.Camera;
import android.util.DisplayMetrics;
import android.util.Log;
import android.view.Surface;
import android.view.SurfaceHolder;
import android.view.SurfaceView;
import android.view.WindowManager;

import java.io.IOException;
import java.util.List;

/**
 * Created by jerry on 10. 10. 2014.
 * A basic Camera Preview class.
 */
public class CameraPreview extends SurfaceView implements SurfaceHolder.Callback {
    private static final String TAG = SurfaceView.class.getSimpleName();
    private SurfaceHolder mHolder;
    private Camera mCamera;
    private Context mContext;
    public List<Camera.Size> mSupportedPreviewSizes;
    private Camera.Size mPreviewSize;
    private Camera.Parameters mParameters;
    private int mRotate;
    private int displayOrientation = 90;
    /**
     * Initializes Camera object and installs a SurfaceHolder.Callback
     * in order to get notified when the underlying surface is created and destroyed.
     *
     * @param context Context of given activity.
     * @param camera Camera object (open Camera).
     *
     */
    public CameraPreview(Context context, Camera camera) {
        super(context);
        mCamera = camera;
        mContext = context;

        if(mCamera != null) {
            mParameters = mCamera.getParameters();
        } else {
            return;
        }

        Log.d(TAG, "CameraPreview constructor");

        if(mParameters.getSupportedPreviewSizes() != null) {
            mSupportedPreviewSizes = mParameters.getSupportedPreviewSizes();
        }


        //Install a SurfaceHolder.Callback so we get notified when the
        //underlying surface is created and destroyed
        mHolder = getHolder();
        mHolder.addCallback(this);
        mHolder.setType(SurfaceHolder.SURFACE_TYPE_PUSH_BUFFERS);
    }

    @Override
    public void surfaceCreated(SurfaceHolder surfaceHolder) {
        //The Surface has been created, now tell the camera where to draw the preview
        Log.d(TAG, "surfaceCreated");

    }

    @Override
    public void surfaceChanged(SurfaceHolder surfaceHolder, int format, int w, int h) {
        // If your preview can change or rotate, take care of those events here.
        // Make sure to stop the preview before resizing or reformatting it.
        Log.d(TAG, "surfaceChanged");

        if (mHolder.getSurface() == null){
            // preview surface does not exist
            return;
        }

        // stop preview before making changes
        try {
            mCamera.stopPreview();
        } catch (Exception e){
            // ignore: tried to stop a non-existent preview
        }

        // set preview size and make any resize, rotate or
        // reformatting changes here
        try {
            try {
                Camera.Parameters params = mCamera.getParameters();

                if(params.getSupportedPreviewSizes() != null) {
                    List<Camera.Size> sizePictures = params.getSupportedPictureSizes();
                    for(Camera.Size s : sizePictures) {
                        Log.d(TAG, s.width + "x" + s.height);
                    }

                    int positionOfSize = 4;
                    if(sizePictures.size() > positionOfSize) {
                        params.setPictureSize(sizePictures.get(positionOfSize).width, sizePictures.get(positionOfSize).height);
                    }

                }

                mRotate = getRotationDegrees();

                params.setFlashMode(Camera.Parameters.FLASH_MODE_AUTO);
                params.setSceneMode(Camera.Parameters.SCENE_MODE_AUTO);
                params.setWhiteBalance(Camera.Parameters.WHITE_BALANCE_AUTO);
                params.setFocusMode(Camera.Parameters.FOCUS_MODE_AUTO);
                params.setJpegQuality(100);
                params.setPreviewSize(mPreviewSize.width, mPreviewSize.height);
                params.setRotation(mRotate);

                mCamera.setParameters(params);
                mCamera.setDisplayOrientation(displayOrientation);
                mCamera.setPreviewDisplay(mHolder);
                mCamera.startPreview();

            } catch (IOException e) {
                Log.d(TAG, "Error setting camera preview: " + e.getMessage());
            }
        } catch (Exception e){
            Log.d(TAG, "Error starting camera preview: " + e.getMessage());
        }
    }

    @Override
    public void surfaceDestroyed(SurfaceHolder holder) {
        // empty. Take care of releasing the Camera preview in your activity.
        Log.d(TAG, "surfaceDestroyed");
        getHolder().removeCallback(this);
        mCamera.stopPreview();
        mCamera.release();
        mCamera = null;
    }

    @Override
    protected void onMeasure(int widthMeasureSpec, int heightMeasureSpec) {
//        super.onMeasure(widthMeasureSpec, heightMeasureSpec);
        final int width = resolveSize(getSuggestedMinimumWidth(), widthMeasureSpec);
        final int height = resolveSize(getSuggestedMinimumHeight(), heightMeasureSpec);
        setMeasuredDimension(width, height);

        if (mSupportedPreviewSizes != null) {
            mPreviewSize = getOptimalPreviewSize(mSupportedPreviewSizes, width, height);
        }
    }

    public Camera.Size getOptimalPreviewSize(List<Camera.Size> sizes, int w, int h) {
        final double ASPECT_TOLERANCE = 0.1;
        double targetRatio=(double)h / w;

        if (sizes == null) return null;

        Camera.Size optimalSize = null;
        double minDiff = Double.MAX_VALUE;

        int targetHeight = h;

        for (Camera.Size size : sizes) {
            double ratio = (double) size.width / size.height;
            if (Math.abs(ratio - targetRatio) > ASPECT_TOLERANCE) continue;
            if (Math.abs(size.height - targetHeight) < minDiff) {
                optimalSize = size;
                minDiff = Math.abs(size.height - targetHeight);
            }
        }

        if (optimalSize == null) {
            minDiff = Double.MAX_VALUE;
            for (Camera.Size size : sizes) {
                if (Math.abs(size.height - targetHeight) < minDiff) {
                    optimalSize = size;
                    minDiff = Math.abs(size.height - targetHeight);
                }
            }
        }
        return optimalSize;
    }

    public int getRotationDegrees() {

        Log.d(TAG, "getRotationDegrees()");
        Camera.CameraInfo info = new Camera.CameraInfo();
        Camera.getCameraInfo(Camera.CameraInfo.CAMERA_FACING_BACK, info);
        DisplayMetrics metrics = new DisplayMetrics();
        WindowManager windowManager = (WindowManager) mContext.getSystemService(Context.WINDOW_SERVICE);
        int rotation = windowManager.getDefaultDisplay().getRotation();
        int degrees = 0;
        switch (rotation) {
            case Surface.ROTATION_0:
                degrees = 0;
                Log.d(TAG, "degrees = 0, Surface.ROTATION_ " + degrees + " = " + Surface.ROTATION_0);
                displayOrientation = 90;
                break; //Natural orientation
            case Surface.ROTATION_90:
                degrees = 90;
                Log.d(TAG, "degrees = " + degrees + ", Surface.ROTATION_ " + degrees + " = " + Surface.ROTATION_90);
                displayOrientation = 0;
                break; //Landscape left
            case Surface.ROTATION_180:
                degrees = 180;
                Log.d(TAG, "degrees = " + degrees + ", Surface.ROTATION_ " + degrees + " = " + Surface.ROTATION_180);
                displayOrientation = 270;
                break;//Upside down
            case Surface.ROTATION_270:
                degrees = 270;
                Log.d(TAG, "degrees = " + degrees + ", Surface.ROTATION_ " + degrees + " = " + Surface.ROTATION_270);
                displayOrientation = 180;
                break;//Landscape right
        }

        return (info.orientation - degrees + 360) % 360;
    }
}
