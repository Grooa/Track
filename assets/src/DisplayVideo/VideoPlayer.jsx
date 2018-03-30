import PropTypes from 'prop-types';

const VideoPlayer = (props) => {
  if (!props.url) {
    return <div
      className="placeholder"
      style={{ backgroundColor: '#212121', height: '20em', width: '100%' }} />;
  }

  return <video
    className="object course-video"
    poster={props.poster}
    controls
    controlsList="nodownload">

    <source src={props.url} type="video/mp4" />
    <p>Your browser do not support the HTML5 videoplayer</p>
  </video>;
};

VideoPlayer.propTypes = {
  url: PropTypes.string,
  poster: PropTypes.string,
};


export default VideoPlayer;
