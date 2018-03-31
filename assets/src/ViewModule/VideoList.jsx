import PropTypes from 'prop-types';

import VideoCard from './VideoCard';

const VideoList = ({ videos }) => <ul className="course-list">
  {videos.map((v, index) => <li key={`video-${index}`}>
    <VideoCard video={v} number={index + 1}/>
  </li>)}
</ul>;

VideoList.propTypes = {
  videos: PropTypes.array.isRequired,
};

export default VideoList;
