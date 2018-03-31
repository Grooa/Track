import PropTypes from 'prop-types';

import CommonCard from '../common/CommonCard';

const VideoCard = ({ video, number }) => {
  const type = `Video ${number}`;
  const description = video.shortDescription || '';
  const hasAccess = !!video.url;
  const button = hasAccess ? 'Checkout' : 'You don\'t have access';

  return <CommonCard
    type={type}
    title={video.title}
    link={hasAccess ? video.url : null}
    description={description}
    buttonLabel={button}
    isDisabled={!hasAccess} />;
};

VideoCard.propTypes = {
  video: PropTypes.object.isRequired,
  number: PropTypes.number.isRequired,
};

export default VideoCard;
