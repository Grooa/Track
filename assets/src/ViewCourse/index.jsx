import React from 'react';
import PropTypes from 'prop-types';
import ReactMarkdown from 'react-markdown';

import CourseApi from './courseApi';

export default class ViewCourse extends React.Component {
  constructor() {
    super();

    this.state = {
      loaded: false,
      course: {},
    };

    this.hasLoaded = this.hasLoaded.bind(this);
    this.hasCourse = this.hasCourse.bind(this);
  }

  componentDidMount() {
    CourseApi.findByLabel(this.props.courseLabel)
      .then(course => this.setState({ course, loaded: true }))
      .catch(console.error);
  }

  hasLoaded() {
    return this.state.loaded;
  }

  hasCourse() {
    return this.state.course !== {};
  }

  render() {
    if (!this.hasLoaded()) {
      return <div>
        <p>Loading course...</p>
      </div>;
    }

    if (this.hasLoaded() && !this.hasCourse()) {
      return <div className="feedback feedback-error">
        <p>Could not find any course with label: {this.props.courseLabel}</p>
      </div>;
    }

    return <article>
      <h1 className="centered">{this.state.course.name}</h1>

      <div className="columns float-top">

        <section className="modules">
          <h2>Modules</h2>

          {this.state.course.modules.length > 0 && <ul className="course-list">
            {this.state.course.modules.map(m => <li className="course-module">
              <a href={m.url}>
                <div className="course-module-metadata">
                  <strong className="type">{m.type} {m.num}</strong>
                  <h3 className="title">{m.title}</h3>
                </div>

                <ReactMarkdown className="description" source={m.shortDescription}/>

                <button>Checkout</button>
              </a>
            </li>)}
          </ul>}
        </section>


        <ReactMarkdown source={this.state.course.description} className="description" />
      </div>
    </article>;
  }
}

ViewCourse.propTypes = {
  courseLabel: PropTypes.string.isRequired,
};
