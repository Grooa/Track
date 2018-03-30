import PropTypes from 'prop-types';

const VideoResources = ({ resources = null, className = '' }) => <section className={className}>
  <h2 className="colored">Resourecs</h2>

  <div className="data">
    {resources && <ul className="course-resources">
      {resources.map((r, key) => <li key={`resource-${key}`}>
        <a href={r.url} title={r.label} target="_blank">{r.label}</a>
      </li>)}
    </ul>}
  </div>
</section>;

VideoResources.propTypes = {
  className: PropTypes.string,
  resources: PropTypes.array,
};

export default VideoResources;
