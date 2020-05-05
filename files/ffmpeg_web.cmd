:: This script converts videos for web
:: Usage: drag a video on top of this script

:: first one: VP9 (webm)
ffmpeg -i %1 -r 40 -deadline best -c:v libvpx-vp9 -pix_fmt yuv420p -b:v 0 -crf 50 -pass 1 -an -f webm NUL && ^
ffmpeg -i %1 -r 40 -deadline best -c:v libvpx-vp9 -pix_fmt yuv420p -b:v 0 -crf 50 -pass 2 -an "%~p1%~n1.webm"

:: second one: h264 (mp4)
ffmpeg -i %1 -r 40 -c:v libx264 -preset placebo -pix_fmt yuv420p -crf 28 -profile:v high -level 4.2 -an -movflags +faststart "%~p1%~n1.mp4"