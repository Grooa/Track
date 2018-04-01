import PropTypes from 'prop-types';
import ReactMarkdown from 'react-markdown';

const VideoDescription = ({ description = '', className = '' }) => <section className={className}>
  <h2>Description</h2>

  {!!description && <ReactMarkdown className="data" source={description} escapeHtml={false} />}
</section>;

VideoDescription.propTypes = {
  description: PropTypes.string,
  className: PropTypes.string,
};

export default VideoDescription;
